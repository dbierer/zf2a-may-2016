<?php

require_once 'PHPUnit/Framework/TestCase.php';
use HootAndHoller\Model\Message;
use HootAndHoller\Service\Messages;
use Zend\EventManager\Event;
use Zend\Di\Di;
use Zend\Di\Config;

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
    private $_testSendData =array(
                            'recipient'	=> 'kevin@zend.com',
                            'sender'	=> 'kschroeder@mirageworks.com',
                            'text'		=> 'Your new album rocks!'
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
        $message->setRecipient('your_name@company.com');
        $message->setSender('recipient@othercompany.com');
        $message->setText('Hello');
        $this->assertTrue($this->Messages->sendMessage($message));
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
            $message->setRecipient('kevin@zend.com');
            $message->setSender('This is a bad email');
            //$message->setSender('doug@zend.com');
            $message->setText('Did this get through?');
            $this->Messages->sendMessage($message);
        } catch (Exception $e) {
            $this->assertSame('Invalid Email', $e->getMessage());
        }
    }

    public function testSendMessageWithDI()
    {
        $di = new Di();
        $message = $di->get('HootAndHoller\Model\Message', $this->_testSendData);
        $this->assertTrue($this->Messages->sendMessage($message));
    }

    public function testSendMessageWithDIConfiguration()
    {
        $diConfig = new Config(
            array(
                'instance'	=> array(
                    'HootAndHoller\Model\Message' => array(
                        'parameters'	=> $this->_testSendData
                    )
                )
            )
        );
        $di = new Di();
        $diConfig->configure($di);
        $message = $di->get('HootAndHoller\Model\Message');
        $this->assertTrue(
            $this->Messages->sendMessage(
                $message
            )
        );
    }

    public function testSendMessageWithDIInjection()
    {

        $diConfig = new Config(
            array(
                'instance'	=> array(
                    'HootAndHoller\Model\Message' => array(
                        'injections'	=> array(
                            'setRecipient' => array('recipient' => 'kevin@zend.com'),
                            'setSender' => array('sender' => 'kschroeder@mirageworks.com'),
                            'setText' => array('text' => 'Your new album rocks!')
                        )
                    )
                )
            )
        );

        $di = new Di();
        $diConfig->configure($di);
        $message = $di->get('HootAndHoller\Model\Message');
        $this->assertSame($message->getRecipient(), 'kevin@zend.com');
        $this->assertSame($message->getSender(), 'kschroeder@mirageworks.com');
        $this->assertSame($message->getText(), 'Your new album rocks!');
        $this->assertTrue($this->Messages->sendMessage($message));
    }
}
