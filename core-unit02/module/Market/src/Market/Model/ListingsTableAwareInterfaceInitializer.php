<?php

namespace Market\Model;

use Market\Model\ListingsTableAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListingsTableAwareInterfaceInitializer implements InitializerInterface
{
    public function initialize($controller, ServiceLocatorInterface $serviceLocator)
    {
        if ($controller instanceof ListingsTableAwareInterface) {
            $controller->setListingsTable($serviceLocator->getServiceLocator()->get('listings-table'));
        }
    }
}
