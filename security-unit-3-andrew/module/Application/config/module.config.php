<?php
return array(
    'router' => array(
        'routes' => array(
            // literal route
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'hoot-and-holler-index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'thisHost' => 'Application\Helper\ThisHost',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
