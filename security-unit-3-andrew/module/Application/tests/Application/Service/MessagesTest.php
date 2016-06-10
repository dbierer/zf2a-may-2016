<?php

require_once 'PHPUnit/Framework/TestCase.php';
use Application\Model\Message;
use Application\Service\Messages;
use Zend\EventManager\Event;
use Zend\ServiceManager\ServiceManager;

/**
 * Messages test case.
 */
class MessagesTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Messages
     */
    private $Messages;
    private $_testData =array(
            'recipient'	=> 'matthew@zend.com',
            'sender'	=> 'doug@unlikelysource.com',
            'text'		=> 'ZF2 rocks!'
    );
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp ();

        // TODO Auto-generated MessagesTest::setUp()

        $this->Messages = new Messages(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated MessagesTest::tearDown()
        $this->Messages = null;

        parent::tearDown ();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    public function testSendMessage()
    {
        $message = new Message();
        $message->setRecipient($this->_testData['recipient']);
        $message->setSender($this->_testData['sender']);
        $message->setText($this->_testData['text']);
        $this->assertInstanceOf('\Application\Model\Message', $this->Messages->sendMessage($message));
    }

    public function testSendMessageFailsWithBadEmail()
    {

        try {
            $this->Messages->someEvent()->attach('message.send', function (Event $e) {
                $message = $e->getTarget();
                /* @var $message Message */
                $email = $message->getSender();
                if (preg_match('/^[a-z0-9-_.]+@(\w+\.)+[a-z]{2,6}$/i', $email)) {
                    throw new \Exception('Email OK');
                } else {
                    throw new \Exception('Invalid Email');
                }
            });

            $message = new Message();
            $message->setRecipient($this->_testData['recipient']);
            $message->setSender('This is a bad email');
            $message->setText($this->_testData['text']);
            $this->Messages->sendMessage($message);
        } catch (Exception $e) {
            $this->assertSame('Invalid Email', $e->getMessage());
        }
    }

    public function testSendMessageWithServiceManager()
    {
        $recipient 	= $this->_testData['recipient'];
        $sender		= $this->_testData['sender'];
        $text		= $this->_testData['text'];
        $serviceManager = new ServiceManager();
        $serviceManager->setFactory('Application\Model\Message',
            function ($serviceManager) use ($recipient, $sender, $text) {
                $message = new Message($recipient, $sender, $text);
                return $message;
            });
        $messageFromServiceManager = $serviceManager->get('Application\Model\Message');
        $messageFromMessageService = $this->Messages->sendMessage($messageFromServiceManager);
        $this->assertEquals($messageFromServiceManager, $messageFromMessageService);
        $this->assertEquals($recipient, $messageFromMessageService->getRecipient());
    }

    public function testSendMessageWithServiceManagerFactory()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setFactory('Application\Model\Message', 'Application\Model\MessageFactory');
        $serviceManager->setService('config', $this->_testData);
        $messageFromServiceManager = $serviceManager->get('Application\Model\Message');
        $messageFromMessageService = $this->Messages->sendMessage($messageFromServiceManager);
        $this->assertEquals($messageFromServiceManager, $messageFromMessageService);
        $this->assertEquals($this->_testData['recipient'], $messageFromMessageService->getRecipient());
    }
}
