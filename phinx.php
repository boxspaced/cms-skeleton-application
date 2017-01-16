<?php
define('PREVENT_RUN', true);
$application = require __DIR__ . '/public/index.php';

$sm = $application->getServiceManager();

$config = $sm->get('config');

return array(
    'paths' => array(
        'migrations' => 'migrations',
    ),
    'environments' => array(
        'default_migration_table' => 'phinx_log',
        'default_database' => 'default',
        'default' => array(
            'adapter' => 'mysql',
            'host' => $config['entity_manager']['db']['hostname'],
            'name' => $config['entity_manager']['db']['database'],
            'user' => $config['entity_manager']['db']['username'],
            'pass' => $config['entity_manager']['db']['password'],
            'port' => '3306',
        )
    )
);
