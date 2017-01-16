<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Router\Http\Literal;
use Boxspaced\CmsItemModule\Controller\ItemController;

return [
    'view_manager' => [
        'doctype' => 'HTML5',
        'display_not_found_reason' => false,
        'display_exceptions' => false,
    ],
    'entity_manager' => [
        'strict' => false,
        'db' => [
            'driver' => 'Pdo_Mysql',
            'driver_options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => ItemController::class,
                        'action' => 'view',
                    ],
                ],
            ],
        ],
    ],
    'editor' => [
        'style_sheet' => '/css/editor.css',
        'html_version' => 'HTML 4.0',
        'max_doc_size' => '10M',
        'max_image_width' => 450,
        'max_image_height' => 0,
        'max_image_size' => '500K',
        'max_media_size' => '50M',
    ],
    'core' => [
        'has_ssl' => true,
        'admin_show_per_page' => 20,
    ],
    'helpdesk' => [
        'attachments_directory' => 'private/helpdesk_attachments',
    ],
    'item' => [
        'enable_meta_fields' => false,
    ],
    'menu' => [
        'max_menu_levels' => 8,
    ],
    'search' => [
        'show_per_page' => 10,
        'index_path' => 'data/indexes/site',
    ],
];
