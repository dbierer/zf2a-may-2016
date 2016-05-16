<?php

namespace Admin\Service;

use Admin\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $controller  = new IndexController();
        $controller->setUsersTable($allServices->get('admin-users-table'));
        $controller->setUserService($allServices->get('admin-user-service'));
        $controller->setServiceManager($allServices);
        return $controller;
    }
}
