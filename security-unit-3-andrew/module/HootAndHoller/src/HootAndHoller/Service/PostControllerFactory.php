<?php

namespace HootAndHoller\Service;

use HootAndHoller\Controller\PostController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $allServices = $services->getServiceLocator();
        $controller  = new PostController();
        $controller->setUsersTable($allServices->get('users-table'));
        $controller->setMessagesTable($allServices->get('messages-table'));
        $controller->setPostForm($allServices->get('hoot-and-holler-post-form'));
        return $controller;
    }
}
