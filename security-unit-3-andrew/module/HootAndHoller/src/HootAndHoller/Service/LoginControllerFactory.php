<?php

namespace HootAndHoller\Service;

use HootAndHoller\Controller\LoginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class LoginControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $controller  = new LoginController();
        $controller->setUsersTable($allServices->get('users-table'));
        $controller->setLoginForm($allServices->get('hoot-and-holler-login-form'));
        $controller->setSessionContainer(new Container('login'));
        /** -- Task: add calls to authAdapter and authService setters */
        $controller->setAuthService($allServices->get('ldap-auth-service'));
        $controller->setAuthAdapter($allServices->get('ldap-auth-adapter'));
		return $controller;
    }
}
