<?php
namespace Application;

use Interop\Container\ContainerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;

class Module
{

    const VERSION = '4.0.0';

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param MvcEvent $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $this->i18n($serviceManager);
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    protected function i18n(ContainerInterface $container)
    {
        $translator = $container->get(Translator::class);
        //$translator->setLocale(...); // @todo obtain from route, browser or database etc.
        AbstractValidator::setDefaultTranslator($translator);
    }

}
