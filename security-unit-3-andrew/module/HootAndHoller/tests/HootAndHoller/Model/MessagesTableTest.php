<?php
use PHPUnit_Framework_TestCase as TestCase;
use HootAndHoller\Model\MessagesTable as MessagesTable;
use Zend\Db\Adapter\Adapter as Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
class MessagesTableTest extends TestCase
{
	private $_messagesTableModel;
	private $_adapter;
	private $_adapterParams = array(
						'driver' 	=> 'pdo_mysql',
						'hostname'	=> '127.0.0.1',
						//'port'		=> 13306,
						'dbname' 	=> 'zf2widder',
						'username' 	=> 'zf2widder',
						'password' 	=> 'password',
	);
	private $_testParams = array(
			 'sender_email' => 'rose@dewitt.com', 
			 'recipient_email' => 'jack@dawson.com',
			 'message' => 'Jack, come back!'
	);
	public function setUp()
	{
		$this->_adapter = new Adapter($this->_adapterParams);
		MessagesTable::setTableName('messages-test');
	}	
	public function teardown()
	{
		// Truncate the table
		$where = new Where();
		$where->isNotNull('message_id');
		$this->_messagesTableModel->delete(function() {});
	}
	public function testMessagesTableConstruct()
	{
		$this->assertInstanceOf('Zend\Db\Adapter\Adapter', $this->_adapter);
		$this->_messagesTableModel = new MessagesTable($this->_adapter, $this->_adapterParams['dbname']);
		$this->assertInstanceOf('HootAndHoller\Model\MessagesTable', $this->_messagesTableModel);
	}
	public function testAdd()
	{
		$this->testMessagesTableConstruct();
		$affected = $this->_messagesTableModel->add($this->_testParams['sender_email'],
													$this->_testParams['message'],
													$this->_testParams['recipient_email']);
		$this->assertEquals(1, $affected);	// rows affected should = 1	
	}
	public function testFetchWithNoSelect()
	{
		$this->testAdd();
		$rows = $this->_messagesTableModel->fetch();
		$this->assertEquals(1, count($rows));
		$this->assertEquals('rose@dewitt.com', $rows[0]['sender_email']);
	}
	public function testFetchWithSelect()
	{
		$this->testAdd();
		$select = new Select();
		$where = new Where();
		$select->from(MessagesTable::getTableName());
		$where->equalTo('sender_email', $this->_testParams['sender_email']);
		$select->where($where);
		$rows = $this->_messagesTableModel->fetch($select);
		$this->assertEquals(1, count($rows));
		$this->assertEquals('rose@dewitt.com', $rows[0]['sender_email']);
	}
}
