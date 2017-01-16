<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

date_default_timezone_set('Europe/London');

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$config = require 'config/application.config.php';

if (file_exists('config/development.config.php')) {
    $config = ArrayUtils::merge($config, require 'config/development.config.php');
}

$application = Application::init($config);

if (!defined('PREVENT_RUN') || PREVENT_RUN === false) {
    $application->run();
}

return $application;
