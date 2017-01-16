<?php

use Boxspaced\CmsCoreModule\Model\ModuleRepository;
use Boxspaced\CmsBlockModule\Model\BlockRepository;
use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Boxspaced\EntityManager\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter as Database;
use Zend\Db\Sql;
use Zend\Log\Logger;
//use Exception;
//use UnexpectedValueException;
//use InvalidArgumentException;
use Boxspaced\CmsItemModule\Service\ItemService;
use Boxspaced\CmsWorkflowModule\Service\WorkflowService;
use Boxspaced\CmsBlockModule\Service\BlockService;
use Boxspaced\CmsItemModule\Service\ItemType;
use Boxspaced\CmsBlockModule\Service\BlockType;
use Boxspaced\CmsItemModule\Service\PublishingOptions as ItemPublishingOptions;
use Boxspaced\CmsBlockModule\Service\PublishingOptions as BlockPublishingOptions;
use Boxspaced\CmsItemModule\Service\FreeBlock;
use Boxspaced\CmsItemModule\Service\BlockSequence;
use Boxspaced\CmsItemModule\Service\BlockSequenceBlock;
use Boxspaced\CmsBlockModule\Service\AvailableBlockOption;
use Boxspaced\CmsItemModule\Service\Item;
use Boxspaced\CmsItemModule\Service\ItemPart;
use Boxspaced\CmsItemModule\Service\ItemField;
use Boxspaced\CmsBlockModule\Service\Block;
use Boxspaced\CmsBlockModule\Service\BlockField;

class AppContext extends BehatContext
{

    /**
     * @var array
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Database
     */
    private $db;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        defined('PREVENT_RUN') || define('PREVENT_RUN', true);
        $application = require __DIR__ . '/../../../../public/index.php';

        $sm = $application->getServiceManager();

        $this->setConfig($sm->get('config'));
        $this->setLogger($sm->get(Logger::class));
        $this->setDb($sm->get(EntityManager::class)->getDb());
        $this->setContainer($sm);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Database
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param array $config
     * @return AppContext
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param Logger $logger
     * @return AppContext
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param Database $db
     * @return AppContext
     */
    public function setDb(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return AppContext
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return MinkContext
     */
    public function getMinkContext()
    {
        return $this->getMainContext()->getMinkContext();
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->getMinkContext()->getSession();
    }

    /**
     * @Given /^a fresh install of the CMS$/
     */
    public function freshInstall()
    {
        exec('sudo bin/phing clear');
        $this->seedBrowserWithRequest();
    }

    /**
     * Visit something so that browser has a request to detect current URL from.
     *
     * This stops error on first use of MinkContext::visitPage when there
     * hasn't been any pages yet.
     *
     * Don't want to call anything that creates 'routes' cache here as this
     * causes permission problems where content created through CLI e.g. from
     * within this context (using the services) does not remove the cache
     * because it was created by www-data user here.
     *
     * @return void
     */
    protected function seedBrowserWithRequest()
    {
        $this->getMinkContext()->visit('/favicon.ico');
    }

    /**
     * @todo use Boxspaced\CmsAccountModule service to create users
     *
     * @Given /^there are users:$/
     */
    public function createUsers(TableNode $table)
    {
        foreach ($table->getHash() as $row) {

            if ('admin' === $row['username']) {
                continue;
            }

            $sql = new Sql\Sql($this->getDb());

            $insert = $sql->insert('user');
            $insert->values([
                'username' => $row['username'],
                'email' => sprintf('%s@localhost', $row['username']),
                'password' => hash($this->getConfig()['account']['password_hashing_algorithm'], 'password'),
            ]);

            $stmt = $sql->prepareStatementForSqlObject($insert);
            $stmt->execute();

            $userId = $this->getDb()->getDriver()->getConnection()->getLastGeneratedValue();

            $roles = explode(',', $row['roles']);

            foreach ($roles as $role) {

                $select = $sql->select();
                $select->columns([
                    'id',
                ]);
                $select->from('role');
                $select->where([
                    'name = ?' => $role,
                ]);

                $stmt = $sql->prepareStatementForSqlObject($select);

                $roleId = $stmt->execute()->getResource()->fetchColumn();

                if (!$roleId) {
                    throw new UnexpectedValueException("Unknown role: {$role}");
                }

                $insert = $sql->insert('user_role');
                $insert->values([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);

                $stmt = $sql->prepareStatementForSqlObject($insert);
                $stmt->execute();
            }
        }
    }

    /**
     * Create items directly in the database via application service layer
     *
     * @Given /^there are items:$/
     */
    public function createItems(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->createItem($row);
        }
    }

    /**
     * @param array $data
     * @return void
     */
    protected function createItem($data)
    {
        foreach (array(
            'name',
            'type',
            'version',
            'stage',
        ) as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("Required data: {$key}");
            }
        }

        $type = $this->getItemTypeByName($data['type']);

        // New
        if ($data['version'] == 'new') {
            $this->createNewItem($data['name'], $type, $data, $data['stage']);
            return;
        }

        // Update
        if ($data['version'] == 'update') {

            $existing = $this->searchForPublishedItemByName($data['name']);
            $id = isset($existing->id) ? $existing->id : null;

            if (!$id) {
                // Create and publish a new item
                $id = $this->createNewItem($data['name'], $type, $data);
            }

            if ($data['stage'] == 'authoring' || $data['stage'] == 'publishing') {

                $id = $this->getService(ItemService::class, 'author')->createRevision($id);
                $item = $this->createServiceItem($type->name, $data['name'], 2);
                $this->getService(ItemService::class, 'author')->edit($id, $item);

                if ($data['stage'] == 'publishing') {

                    $this->getService(WorkflowService::class, 'author')->moveToPublishing('Item', $id);
                }
            }
        }
    }

    /**
     * Create blocks directly in the database via application service layer
     *
     * @Given /^there are blocks:$/
     */
    public function createBlocks(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->createBlock($row);
        }
    }

    /**
     * @param array $data
     * @return void
     */
    protected function createBlock($data)
    {
        foreach (array(
            'name',
            'type',
            'version',
            'stage',
        ) as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("Required data: {$key}");
            }
        }

        $type = $this->getBlockTypeByName($data['type']);

        // New
        if ($data['version'] == 'new') {
            $this->createNewBlock($data['name'], $type, $data, $data['stage']);
            return;
        }

        // Update
        if ($data['version'] == 'update') {

            $existing = $this->searchForPublishedBlockByName($data['name']);
            $id = isset($existing->id) ? $existing->id : null;

            if (!$id) {
                // Create and publish a new block
                $id = $this->createNewBlock($data['name'], $type, $data);
            }

            if ($data['stage'] == 'authoring' || $data['stage'] == 'publishing') {

                $id = $this->getService(BlockService::class, 'author')->createRevision($id);
                $block = $this->createServiceBlock($type->name, $data['name'], 2);
                $this->getService(BlockService::class, 'author')->edit($id, $block);

                if ($data['stage'] == 'publishing') {

                    $this->getService(WorkflowService::class, 'author')->moveToPublishing('Block', $id);
                }
            }
        }
    }

    /**
     * @Given /^the menu is empty$/
     */
    public function emptyMenu()
    {
        $sql = new Sql\Sql($this->getDb());

        $delete = $sql->delete('menu_item');

        $stmt = $sql->prepareStatementForSqlObject($delete);
        $stmt->execute();
    }

    /**
     * @param string $name
     * @return ItemType
     */
    protected function getItemTypeByName($name)
    {
        $types = $this->getService(ItemService::class, 'author')->getTypes();

        $found = null;

        foreach ($types as $type) {

            if ($type->name == $name) {
                $found = $type;
            }
        }

        if (null === $found) {
            throw new UnexpectedValueException("Type not found: {$name}");
        }

        return $found;
    }

    /**
     * @param string $name
     * @return BlockType
     */
    protected function getBlockTypeByName($name)
    {
        $types = $this->getService(BlockService::class, 'author')->getTypes();

        $found = null;

        foreach ($types as $type) {

            if ($type->name == $name) {
                $found = $type;
            }
        }

        if (null === $found) {
            throw new UnexpectedValueException("Type not found: {$name}");
        }

        return $found;
    }

    /**
     * @param string $name
     * @param ItemType $type
     * @param array $data
     * @param string $stage
     * @return int
     */
    protected function createNewItem(
        $name,
        ItemType $type,
        array $data,
        $stage = null
    )
    {
        $id = $this->getService(ItemService::class, 'author')->createDraft($name, $type->id);
        $item = $this->createServiceItem($type->name, $name, 1);
        $this->getService(ItemService::class, 'author')->edit($id, $item);

        if ($stage == 'authoring') {
            // Keep in authoring workflow
            return $id;
        }

        if ($stage == 'publishing') {
            // Move to publishing workflow
            $this->getService(WorkflowService::class, 'author')->moveToPublishing('Item', $id);
            return $id;
        }

        // Publish
        $availableLocationOptions = $this->getService(ItemService::class, 'publisher')->getAvailableLocationOptions($id);
        $availableColourSchemeOptions = $this->getService(ItemService::class, 'publisher')->getAvailableColourSchemeOptions();

        $teaserTemplateId = 4;
        if (isset($data['teaserTemplate'])) {
            foreach ($type->teaserTemplates as $teaserTemplate) {
                if ($teaserTemplate->name == $data['teaserTemplate']) {
                    $teaserTemplateId = $teaserTemplate->id;
                }
            }
        }

        $templateId = 10;
        if (isset($data['template'])) {
            foreach ($type->templates as $template) {
                if ($template->name == $data['template']) {
                    $templateId = $template->id;
                }
            }
        }

        $colourScheme = 'dark-blue';
        if (isset($data['colourScheme'])) {
            foreach ($availableColourSchemeOptions as $option) {
                if ($option->label == $data['colourScheme']) {
                    $colourScheme = $option->value;
                }
            }
        }

        $publishTo = 'Standalone';
        if (isset($data['publishTo'])) {
            foreach ($availableLocationOptions->toOptions as $option) {
                if ($option->label == $data['publishTo']) {
                    $publishTo = $option->value;
                }
            }
        }

        $publishBeneathMenuItemId = 0; // Top level
        if (isset($data['publishBeneathMenuItem'])) {
            foreach ($availableLocationOptions->beneathMenuItemOptions as $option) {
                if ($option->label == $data['publishBeneathMenuItem']) {
                    $publishBeneathMenuItemId = $option->value;
                }
            }
        }

        $liveFrom = new DateTime(isset($data['liveFrom']) ? $data['liveFrom'] : '2000-01-01 00:00:00');
        $expiresEnd = new DateTime(isset($data['expiresEnd']) ? $data['expiresEnd'] : '2038-01-19 00:00:00');

        $publishingOptions = new ItemPublishingOptions();
        $publishingOptions->name = $name;
        $publishingOptions->colourScheme = $colourScheme;
        $publishingOptions->liveFrom = $liveFrom;
        $publishingOptions->expiresEnd = $expiresEnd;
        $publishingOptions->teaserTemplateId = $teaserTemplateId;
        $publishingOptions->templateId = $templateId;
        $publishingOptions->to = $publishTo;

        if (ItemService::PUBLISH_TO_MENU === $publishTo) {
            $publishingOptions->beneathMenuItemId = $publishBeneathMenuItemId;
        }

        if (isset($data['mainImage'])) {

            $publishingOptions->freeBlocks[] = $this->createFreeBlock(
                $id,
                'mainImage',
                $data['mainImage']
            );
        }

        if (isset($data['lowerPromo'])) {

            $publishingOptions->freeBlocks[] = $this->createFreeBlock(
                $id,
                'lowerPromo',
                $data['lowerPromo']
            );
        }

        if (isset($data['leftColumn'])) {

            $publishingOptions->blockSequences[] = $this->createBlockSequence(
                $id,
                'leftColumn',
                explode(',', $data['leftColumn'])
            );
        }

        if (isset($data['rightColumn'])) {

            $publishingOptions->blockSequences[] = $this->createBlockSequence(
                $id,
                'rightColumn',
                explode(',', $data['rightColumn'])
            );
        }

        $this->getService(ItemService::class, 'publisher')->publish($id, $publishingOptions);

        return $id;
    }

    /**
     * @param int $contentId
     * @param string $freeBlockName
     * @param string $blockName
     * @return FreeBlock
     */
    protected function createFreeBlock($contentId, $freeBlockName, $blockName)
    {
        $blockOption = $this->getAvailableBlockOption($contentId, 'html', $blockName);

        $freeBlock = new FreeBlock();
        $freeBlock->name = $freeBlockName;
        $freeBlock->id = $blockOption->value;

        return $freeBlock;
    }

    /**
     * @param int $contentId
     * @param string $sequenceName
     * @param string[] $blockNames
     * @return BlockSequence
     */
    protected function createBlockSequence($contentId, $sequenceName, array $blockNames)
    {
        $blockSequence = new BlockSequence();
        $blockSequence->name = $sequenceName;

        foreach ($blockNames as $key => $blockName) {

            $blockOption = $this->getAvailableBlockOption($contentId, 'html', $blockName);

            $blockSequenceBlock = new BlockSequenceBlock();
            $blockSequenceBlock->id = $blockOption->value;
            $blockSequenceBlock->orderBy = $key;

            $blockSequence->blocks[] = $blockSequenceBlock;
        }

        return $blockSequence;
    }

    /**
     * @param int $contentId
     * @param string $blockTypeName
     * @param string $blockName
     * @return AvailableBlockOption
     */
    protected function getAvailableBlockOption($contentId, $blockTypeName, $blockName)
    {
        $availableBlockOptions = $this->getService(BlockService::class, 'publisher')->getAvailableBlockOptions($contentId);

        $typeOption = current(array_filter($availableBlockOptions, function($option) use ($blockTypeName) {
            return ($option->name === $blockTypeName);
        }));

        return current(array_filter($typeOption->blockOptions, function($option) use ($blockName) {
            return ($option->label === $blockName);
        }));
    }

    /**
     * @param string $name
     * @param BlockType $type
     * @param array $data
     * @param string $stage
     * @return int
     */
    protected function createNewBlock(
        $name,
        BlockType $type,
        array $data,
        $stage = null
    )
    {
        $id = $this->getService(BlockService::class, 'author')->createDraft($name, $type->id);
        $block = $this->createServiceBlock($type->name, $name, 1);
        $this->getService(BlockService::class, 'author')->edit($id, $block);

        if ($stage == 'authoring') {
            // Keep in authoring workflow
            return $id;
        }

        if ($stage == 'publishing') {
            // Move to publishing workflow
            $this->getService(WorkflowService::class, 'author')->moveToPublishing('Block', $id);
            return $id;
        }

        // Publish
        $templateId = 2;
        if (isset($data['template'])) {
            foreach ($type->templates as $template) {
                if ($template->name == $data['template']) {
                    $templateId = $template->id;
                }
            }
        }

        $liveFrom = new DateTime(isset($data['liveFrom']) ? $data['liveFrom'] : '2000-01-01 00:00:00');
        $expiresEnd = new DateTime(isset($data['expiresEnd']) ? $data['expiresEnd'] : '2038-01-19 00:00:00');

        $publishingOptions = new BlockPublishingOptions();
        $publishingOptions->name = $name;
        $publishingOptions->liveFrom = $liveFrom;
        $publishingOptions->expiresEnd = $expiresEnd;
        $publishingOptions->templateId = $templateId;

        $this->getService(BlockService::class, 'publisher')->publish($id, $publishingOptions);

        return $id;
    }

    /**
     * @param string $type
     * @param string $name
     * @param int $version
     * @return Item
     */
    protected function createServiceItem($type, $name, $version)
    {
        switch ($type) {

            case 'article':

                $navText = sprintf('%s-v%d-navText', $name, $version);
                $title = sprintf('%s-v%d-title', $name, $version);
                $intro = sprintf('%s-v%d-intro', $name, $version);
                $body = sprintf('%s-v%d-body', $name, $version);

                $item = new Item();
                $item->navText = $navText;
                $item->title = $title;

                $part = new ItemPart();
                $part->orderBy = 0;

                $field = new ItemField();
                $field->name = 'intro';
                $field->value = $intro;
                $part->fields[] = $field;

                $field = new ItemField();
                $field->name = 'body';
                $field->value = $body;
                $part->fields[] = $field;

                $item->parts[] = $part;

                return $item;

            default:
                throw new InvalidArgumentException("Unknown type: {$type}");
        }
    }

    /**
     * @param string $type
     * @param string $name
     * @param int $version
     * @return Block
     */
    protected function createServiceBlock($type, $name, $version)
    {
        switch ($type) {

            case 'html':

                $html = sprintf('%s-v%d-html', $name, $version);

                $block = new Block();

                $field = new BlockField();
                $field->name = 'html';
                $field->value = $html;
                $block->fields[] = $field;

                return $block;

            default:
                throw new InvalidArgumentException("Unknown type: {$type}");
        }
    }

    /**
     * @param string $name
     * @return Item
     */
    protected function searchForPublishedItemByName($name)
    {
        $module = $this->getContainer()->get(ModuleRepository::class)->getByName('item');

        $id = null;
        foreach ($module->getRoutes() as $route) {
            if ($route->getSlug() == $name) {
                $id = $route->getIdentifier();
            }
        }

        if (!$id) {
            return null;
        }

        try {
            $item = $this->getService(ItemService::class, 'author')->getItem($id);
            $this->getService(ItemService::class, 'author')->getCurrentPublishingOptions($id);
        } catch (Exception $e) {
            return null;
        }

        return $item;
    }

    /**
     * @todo use Boxspaced\CmsBlockModule service rather than repository
     *
     * @param string $name
     * @return Block
     */
    protected function searchForPublishedBlockByName($name)
    {
        $block = $this->getContainer()->get(BlockRepository::class)->getByName($name);

        if (!$block) {
            return null;
        }

        $id = $block->getId();

        try {
            $block = $this->getService(BlockService::class, 'author')->getBlock($id);
            $this->getService(BlockService::class, 'author')->getCurrentPublishingOptions($id);
        } catch (Exception $e) {
            return null;
        }

        return $block;
    }

    /**
     * @param string $name
     * @param string $username
     * @return mixed
     */
    protected function getService($name, $username)
    {
        $this->authenticate($username);
        return $this->getContainer()->get($name);
    }

    /**
     * @todo use Boxspaced\CmsAccountModule service
     *
     * @param string $username
     */
    protected function authenticate($username)
    {
        $sql = new Sql\Sql($this->getDb());

        $select = $sql->select();
        $select->columns([
            'id',
        ]);
        $select->from('user');
        $select->where([
            'username = ?' => $username,
        ]);

        $stmt = $sql->prepareStatementForSqlObject($select);

        $userId = $stmt->execute()->getResource()->fetchColumn();

        if (!$userId) {
            throw new UnexpectedValueException("Internal authentication failed with username: {$username}");
        }

        $data = new stdClass();
        $data->id = $userId;
        $this->getContainer()->get(AuthenticationService::class)->getStorage()->write($data);
    }

}
