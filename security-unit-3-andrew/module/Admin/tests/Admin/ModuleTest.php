<?php
require_once 'PHPUnit/Framework/TestCase.php';
use Admin\Module;

/**
 * Messages test case.
 */
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
		$this->assertInstanceOf('Admin\Module', $this->_module);
	}
	
}
