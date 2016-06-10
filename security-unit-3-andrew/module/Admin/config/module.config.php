<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'admin-user-delete-form' 	=> 'Admin\Form\UserDeleteForm',
            'admin-user-delete-filter' 	=> 'Admin\Form\UserDeleteFilter',
            'admin-auth-service'		=> 'Zend\Authentication\AuthenticationService',
        ),
        'factories' => array(
            'admin-users-table' 		=> 'Admin\Service\UsersTableFactory',
            'admin-user-service' 		=> function ($services) {
                    return new Admin\Service\UserService($services->get('admin-users-table'));
            },
            'admin-user-info-filter' 	=> function ($services) {
                    $filter = new Admin\Form\UserInfoFilter();
                    $filter->prepareFilters();
                    return $filter;
            },
            'admin-user-info-form' 		=> function ($services) {
                $form = new Admin\Form\UserInfoForm();
                $form->prepareElements();
                $form->setInputFilter($services->get('admin-user-info-filter'));
                return $form;
            },
        )
    ),
    'controllers' => array(
        'factories' => array(
            'admin-index' => 'Admin\Service\IndexControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin[/:action]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' 	 => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'admin-index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'admin-index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        //'base_path' => $stringBasePath,		// might need this for the cloud
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'layout'				   => 'layout/layout',
        'template_map' => array(
            'admin-index/index'   => __DIR__ . '/../view/admin/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
