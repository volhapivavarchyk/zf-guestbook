<?php
namespace Guestbook;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'guestbook' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/guestbook[/:action[/:id]]',
                    'constrains' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\GuestbookController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'guestbook' => __DIR__.'/../view',
        ],
    ],
];
