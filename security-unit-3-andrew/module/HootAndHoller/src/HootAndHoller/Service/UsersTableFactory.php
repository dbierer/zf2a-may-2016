<?php

namespace HootAndHoller\Service;

use HootAndHoller\Model\UsersTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class UsersTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $adapter   = $services->get('general-adapter');
        return new UsersTable($adapter);
    }
}
