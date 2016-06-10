<?php

$dns = explode('.', $_SERVER['HTTP_HOST']);
define('CONTAINER', $dns[0]);
define('CONTAINER_PASSWORD', 'password');
if (isset($dns[2]) && $dns[2] == 'phpcloud') {
    define('HOME_URL', '/zf2widdercomplete/');
} else {
    define('HOME_URL', '/');
}
return array(
    'modules' => array(
        'Application',
        'HootAndHoller',
        //'Admin'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array('config/autoload/{,*.}{global,local}.php',),
        'module_paths' => array('./module', './vendor',),
    ),
    'service_manager' => array(
        'use_defaults' => true,
    ),
    'autoloader_options' => array(
        'fallback_autoloader' => true,
    ),
);
