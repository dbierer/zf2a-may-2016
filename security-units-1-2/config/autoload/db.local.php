<?php
/**
 * Local Configuration Override
 */

return array(
    'service_manager' => array(
        'factories' => array(
            // uses the 'db' key by default
            'general-adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    // The 'db' key has special significance
    // look at the source code for Zend\Db\Adapter\AdapterServiceFactory
    'db' => array(
        'driver'         => 'pdo',
        'dsn'            => 'mysql:dbname=onlinemarket;host=localhost',
        'username'       => 'zend',
        'password'       => 'password',
        'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                // NOTE: change to PDO::ERRMODE_SILENT for production!
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING),
    ),
);
