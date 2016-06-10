<?php

namespace Status;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Status\TableGateway' => function ($services) {
                $adapter   = $services->get('Db\StatusApi');
                $hydrators = $services->get('HydratorManager');
                $hydrator  = $hydrators->get('ClassMethods');
                $prototype = new HydratingResultSet($hydrator, new Status());

                return new TableGateway('status', $adapter, null, $prototype);
            },
            'Status\Listener' => function ($services) {
                $table = $services->get('Status\TableGateway');
                return new StatusListener($table);
            },
        ));
    }
}
