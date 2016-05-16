<?php

namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Headers;

class Module implements AutoloaderProviderInterface
{
	protected $_config;
	
	public function onBootstrap(MvcEvent $e)
	{
		$em = $e->getApplication()->getEventManager();
		$em->attach('dispatch', array($this, 'accessCheck'));
	}
	
	public function accessCheck(MvcEvent $e)
	{
	 	$matches    = $e->getRouteMatch();
	 	$controller = $matches->getParam('controller');
	 	if ($controller == 'admin-index') {
			$sm = $e->getTarget()->getServiceLocator()->get('ServiceManager');
			$authService = $sm->get('admin-auth-service');
			/**
			 * If not admin user, redirects home
			 * @return boolean | redirect()
			 */
			$goHome = TRUE;
			if ($authService->hasIdentity()) {
				if ($authService->getIdentity()->email == 'admin@zend.com') {
					$goHome = FALSE;
				}
			}
			if ($goHome) {
		 		$headers = new Headers();
		 		$headers->addHeaderLine('Location: ' . HOME_URL);
		 		$response = $e->getResponse();
		 		$response->setStatusCode('302');
		 		$response->setHeaders($headers);
		 		return $response;
			}
	 	}
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
		$this->_config = include __DIR__ . '/config/module.config.php';
        return $this->_config;
    }
}
