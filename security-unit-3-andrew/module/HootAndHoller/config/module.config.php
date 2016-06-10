<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'hoot-and-holler-login-filter' => 'HootAndHoller\Form\LoginFilter',
            'hoot-and-holler-post-filter' => 'HootAndHoller\Form\HootAndHollerFilter',
        ),
        'factories' => array(
            'messages-table' => 'HootAndHoller\Service\MessagesTableFactory',
            'users-table' => 'HootAndHoller\Service\UsersTableFactory',
            'hoot-and-holler-login-form' => 'HootAndHoller\Service\LoginFormFactory',
            'hoot-and-holler-post-form' => 'HootAndHoller\Service\HootAndHollerFormFactory',
        )
    ),
    'controllers' => array(
        'aliases' => array(
            'hoot-and-holler-index' => 'hoot-and-holler-index-factory',
            'login-index' => 'hoot-and-holler-login-factory',
            'post-index' => 'hoot-and-holler-post-factory',
        ),
        'factories' => array(
            'hoot-and-holler-index-factory' => 'HootAndHoller\Service\IndexControllerFactory',
            'hoot-and-holler-login-factory' => 'HootAndHoller\Service\LoginControllerFactory',
            'hoot-and-holler-post-factory' => 'HootAndHoller\Service\PostControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'hoots' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/hoot[s][/]',
                    'defaults' => array(
                        'controller' => 'hoot-and-holler-index',
                        'action'     => 'hoot',
                    ),
                ),
            ),
            'hollers' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/holler[s][/]',
                    'defaults' => array(
                        'controller' => 'hoot-and-holler-index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'post' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/post[/]',
                    'defaults' => array(
                        'controller' => 'post-index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login-index' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/login[/]',
                    'defaults' => array(
                        'controller' => 'login-index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // literal route
            'login-invalid' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login/invalid',
                    'defaults' => array(
                        'controller' => 'login-index',
                        'action'     => 'invalid',
                    ),
                ),
            ),
            // literal route
            'login-logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/logout[/]',
                    'defaults' => array(
                        'controller' => 'login-index',
                        'action'     => 'logout',
                    ),
                ),
            ),
            // user confirmation route
            'confirm' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/login/confirm[/:lastname][/:hash]',
                    'constraints' => array(
                        'lastname' => '[a-zA-Z][a-zA-Z_.@-]*',
                        'hash' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'login-index',
                        'action' => 'confirm',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
