<?php

namespace HootAndHoller\Service;

use HootAndHoller\Model\MessagesTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class MessagesTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $adapter   = $services->get('general-adapter');
        return new MessagesTable($adapter);
    }
}
