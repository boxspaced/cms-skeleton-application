<?php
namespace Application;

use Zend\I18n\Translator\Loader\PhpArray;
use Zend\Db\Adapter\AdapterInterface;

return [
    'service_manager' => [
        'factories' => [
            AdapterInterface::class => Db\AdapterFactory::class,
        ]
    ],
    'translator' => [
        'locale' => 'en', // default
        'translation_file_patterns' => [
            [
                'type' => PhpArray::class,
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ],
        ],
    ],
    'view_manager' => [
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
