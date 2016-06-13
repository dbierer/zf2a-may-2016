<?php
/* Alternate configurations:
 // NOTE: 'db' key is used by the adapter factory
'db-cloud' => array(
        'driver' 	=> 'pdo_mysql',
        'dsn'		=> 'mysql:dbname=' . CONTAINER . ';host=' . CONTAINER . '-db.my.phpcloud.com;port=3306',
        'username' 	=> CONTAINER,
        'password' 	=> CONTAINER_PASSWORD,
),
'db-normal' => array(
        'driver' 	=> 'pdo_mysql',
        'dbname' 	=> CONTAINER,
        'host'		=> CONTAINER . '-db.my.phpcloud.com',
        'port'		=> 3306,
        'username' 	=> CONTAINER,
        'password' 	=> CONTAINER_PASSWORD,
),
'db-local' => array(
        'driver' 	=> 'pdo_mysql',
        'dbname' 	=> 'zf2widder',
        'host'		=> 'zf2widder.complete',
        'port'		=> 3306,
        'username' 	=> 'test',
        'password' 	=> 'password',
),

*/
// determine if on cloud or not
if (stripos($_SERVER['SERVER_NAME'], 'phpcloud.com')) {
    $dbname = CONTAINER;
    $host = CONTAINER . '-db.my.phpcloud.com';
    $username = CONTAINER;
    $password = CONTAINER_PASSWORD;
} else {
    $dbname = 'zf2widder';
    $host = 'localhost';
    $username = 'zend';
    $password = 'password';
}
$driverOptions = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                        // NOTE: change to PDO::ERRMODE_SILENT for production!
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

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
            'driver' 	     => 'pdo_mysql',
            'dbname' 	     => $dbname,
            'host'		     => $host,
            'port'		     => 3306,
            'username' 	     => $username,
            'password' 	     => $password,
            'driver_options' => $driverOptions,
    ),
);
