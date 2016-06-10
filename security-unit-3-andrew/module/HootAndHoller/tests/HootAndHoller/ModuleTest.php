<?php
require_once 'PHPUnit/Framework/TestCase.php';
use HootAndHoller\Module;
use Zend\ModuleManager\Listener;
use Zend\ModuleManager\ModuleManager;
use Zend\Version;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    private $_module;
    public function setUp()
    {
        $this->_module = new Module();
        Zend\Loader\AutoloaderFactory::factory($this->_module->getAutoloaderConfig());
    }
    public function testModuleBootstrap()
    {
        $this->assertInstanceOf('HootAndHoller\Module', $this->_module);
    }
    public function testModuleLoadUsingServiceManager()
    {
        $configuration = require __DIR__ . '/../../../../config/application.config.php';
        $serviceManager = new ServiceManager(new ServiceManagerConfig($configuration['service_manager']));
        $serviceManager->setService('ApplicationConfig', $configuration);
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModule('HootAndHoller');
        $module = $moduleManager->getModule('HootAndHoller');
        $this->assertInstanceOf('HootAndHoller\Module', $module);
    }
    public function testModuleManagerWithDefaultListenerAggregates()
    {
        $defaultListeners = new Listener\DefaultListenerAggregate();
        $moduleManager = new ModuleManager(array('HootAndHoller'));
        $moduleManager->getEventManager()->attachAggregate($defaultListeners);
        $moduleManager->loadModule('HootAndHoller');
        $this->assertArrayHasKey('HootAndHoller', $moduleManager->getLoadedModules());
        return $moduleManager;
    }
    public function testModuleLoadModulesPostEvent()
    {
        $moduleManager = $this->testModuleManagerWithDefaultListenerAggregates();
        $module = $moduleManager->getModule('HootAndHoller');
        $moduleManager->loadModules();
        $this->assertInstanceOf('HootAndHoller\Module', $module);
        $this->assertSame('TEST', $module->a);
        $this->assertArrayHasKey('HootAndHoller', $module->b);
    }
}
