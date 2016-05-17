<?php
use HootAndHoller\Module;
error_reporting( E_ALL | E_STRICT );
define('ZF2_LIBRARY',realpath(__DIR__ .'/../../../vendor/ZendFramework/library'));
$path = array(ZF2_LIBRARY, __DIR__, get_include_path(),);
set_include_path(implode(PATH_SEPARATOR, $path));

// Copied from zf2widder.complete/public/index.php
// Get application stack configuration
$configuration = include __DIR__ . '/../../../config/application.config.php';

require_once ZF2_LIBRARY . '/Zend/Loader/StandardAutoloader.php';
use Zend\Loader\StandardAutoloader as StandardAutoloader;

$autoloader = new StandardAutoloader();
$autoloader->setOptions($configuration['autoloader_options']);
$autoloader->register();

require_once 'Zend/Loader/AutoloaderFactory.php';
require_once '../Module.php';
$module = new Module();
Zend\Loader\AutoloaderFactory::factory($module->getAutoloaderConfig());
