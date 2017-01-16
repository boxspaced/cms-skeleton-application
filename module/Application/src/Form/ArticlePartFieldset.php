<?php
namespace Application\Form;

use Zend\Form\Element;
use Zend\Filter;
use Boxspaced\CmsItemModule\Form\AbstractItemPartFieldset;

class ArticlePartFieldset extends AbstractItemPartFieldset
{

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $element = new Element\Textarea('intro');
        $element->setLabel('Introduction');
        $element->setAttributes([
            'class' => 'wysiwyg',
            'rows' => 4,
            'cols' => 60,
        ]);
        $this->add($element);

        $element = new Element\Textarea('body');
        $element->setLabel('Body');
        $element->setAttributes([
            'class' => 'wysiwyg',
            'rows' => 4,
            'cols' => 60,
        ]);
        $this->add($element);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return parent::getInputFilterSpecification() + [
            'intro' => [
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                ],
            ],
            'body' => [
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                ],
            ],
        ];
    }

}
