<?php

namespace HootAndHoller\Service;

use HootAndHoller\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $controller  = new IndexController();
        $controller->setMessagesTable($allServices->get('messages-table'));
        return $controller;
    }
}
