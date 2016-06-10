<?php
use PHPUnit_Framework_TestCase as TestCase;
use HootAndHoller\Controller\PostController;
use HootAndHoller\Model\MessagesTable;
use HootAndHoller\Model\UsersTable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\View\Model\ViewModel;
class PostControllerTest extends TestCase
{
    private $_postController;
    private $_messagesTable;
    private $_usersTable;
    private $_adapterParams = array(
            'driver' 	=> 'pdo_mysql',
            'hostname'	=> '127.0.0.1',
            //'port'		=> 13306,
            'dbname' 	=> 'zf2widder',
            'username' 	=> 'zf2widder',
            'password' 	=> 'password',
    );

    public function setUp()
    {
        $adapter = new Adapter($this->_adapterParams);
        $this->_messagesTable = new MessagesTable($adapter);
        MessagesTable::setTableName('messages-test');
        $this->_usersTable = new UsersTable($adapter);
        $this->_usersTable->setTableName('users-test');
        $this->_postController = new PostController();
        $this->_postController->setMessagesTable($this->_messagesTable);
        $this->_postController->setUsersTable($this->_usersTable);
    }
    public function teardown()
    {
        // Truncate test tables
        $this->_messagesTable->delete(function () {});
        $this->_usersTable->delete(function () {});
    }
    public function testPostControllerConstruct()
    {
        $this->assertInstanceOf('HootAndHoller\Controller\PostController', $this->_postController);
    }
    public function testIndexActionDefault()
    {
        // need to set up a request object ... ???
        //$viewModel = $this->_postController->indexAction();
        //$this->assertInstanceOf('Zend\View\Model\ViewModel', $viewModel);
        //var_dump($viewModel); exit;
    }
}
