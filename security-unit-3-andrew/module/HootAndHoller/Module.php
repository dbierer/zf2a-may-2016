<?php

namespace HootAndHoller;
use Zend\Mvc\MvcEvent;
use HootAndHoller\Controller;
use HootAndHoller\Model\Acl;
use HootAndHoller\Model\MessagesTable;
use HootAndHoller\Model\UsersTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\EventManager\EventInterface as Event;
use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Headers;
use Zend\Mail\Message;

class Module implements AutoloaderProviderInterface
{
	
	public function onBootstrap($e)
	{
	     $eventManager = $e->getApplication()->getEventManager();
	     $sharedEm = $eventManager->getSharedManager();
	     $sharedEm->attach('login', 'email-notification', array($this, 'onEmailNotification'));
	}    
	
	public function onEmailNotification(Event $e)
	{
		$data = $e->getParam('data');
		$serviceManager = $e->getTarget()->getServiceLocator()->get('ServiceManager');
		// build confirmation email
		$returnMessage = '';
		//$hash = md5(date('Y-m-d H:i:s') . rand(1,999));
		$hash = $data['description'];
		$url = sprintf('http://%s/login/confirm/%s/%s',
				$_SERVER['HTTP_HOST'], urlencode($data['lastname']), $hash);
		$body = 'Welcome to zf2widder ' . $data['realName'] . '!' . PHP_EOL
		. '<br />Please click on this link in order to confirm your account: ' . PHP_EOL
		. '<br /><a href="' . $url . '">CONFIRM</a>' . PHP_EOL;
		$mail = new Message();
		$mail->addTo($data['email'])
			 ->setSender('admin@zend.com')
			 ->setSubject('Please Confirm Your zf2widder Account!')
			 ->setBody($body);
		$transport = $serviceManager->get('mail-transport');
		try {
			$transport->send($mail);
		} catch (Exception\InvalidArgumentException $e) {
			$returnMessage = '<br />Invalid Argument: ' . $e->getMessage();
		} catch (Exception\RuntimeException $e) {
			$returnMessage = '<br />Runtime Exception: ' . $e->getMessage();
		}
		return $returnMessage;
	} 
	
 	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
	}
	
	public function getConfig()
	{
	    return include __DIR__ . '/config/module.config.php';
	}

}