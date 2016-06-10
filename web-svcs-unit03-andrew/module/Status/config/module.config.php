<?php

return array(
    'phlyrestfully' => array(
        'metadata_map' => array(
            'Status\Status' => array(
                // Fill in the "hydrator" and "route" fields
                'hydrator' => 'ClassMethods',
                'route'    => 'status-api',
            ),
            'Status\Statuses' => array(
                'is_collection' => true,
                'route'         => 'status-api',
            ),
        ),
        'resources' => array(
            'Status\StatusController' => array(
                // Fill in the "listener", "collection_name", "page_size", and 
                // "route_name" fields
                'identifier'           => 'Status\StatusController',
                'listener'             => 'Status\Listener',
                'resource_identifiers' => 'StatusResource',
                'collection_name'      => 'status',
                'page_size'            => '10',
                'route_name'           => 'status-api',
            ),
        ),
    ),
    'db' => array('adapters' => array(
        'Db\StatusApi' => array(
            'driver'   => 'Pdo_Sqlite',
            'database' => 'data/db/status.db',
        ),
    )),
    'router' => array('routes' => array(
        'status-api' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/api/status[/:id]',
                'constraints' => array(
                    'id' => '[a-f0-9]{32}',
                ),
                'defaults' => array(
                    'controller' => 'Status\StatusController',
                ),
            ),
        ),
    )),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
);
