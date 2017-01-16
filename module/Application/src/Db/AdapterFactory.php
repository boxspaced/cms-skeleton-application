<?php
namespace Application\Db;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Boxspaced\EntityManager\EntityManager;

class AdapterFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get(EntityManager::class)->getDb();
    }

}
