<?php
require_once 'PHPUnit/Framework/TestCase.php';
use Admin\Model\UsersTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class UsersTableTest extends PHPUnit_Framework_TestCase 
{
	private $_adapter;
	private $_table;
	
	public function setup()
	{
		$config = require __DIR__ . '/../../../config/module.config.php';
		$this->_adapter = new Adapter($config['db']);
		$this->_table = new UsersTable($this->_adapter);
		$this->_table->setTableName('users-test');
	}
	public function teardown()
	{
		$this->_adapter = NULL;
	}

	public function testAdapter()
	{
		$this->assertInstanceOf('Zend\Db\Adapter\Adapter', $this->_adapter);
	}
	
	public function testTableInstance()
	{
		$this->assertInstanceOf('Admin\Model\UsersTable', $this->_table);
	}
}
