<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
		$em = $e->getApplication()->getEventManager();
		$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
		$em->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));
    }
    
    public function onDispatch(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$e->getViewModel()->setVariable('languageForm', $sm->get('application-switch-language-form'));
    }

    public function onRender(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$language = $e->getRequest()->getQuery('language');
    	if ($language) {
    		$languageList = $sm->get('languages');
    		if (in_array($language, array_keys($languageList), TRUE)) {
    			//$translator = $sm->get('translator');
    			$translator = $sm->get('MvcTranslator');
    			$translator->setLocale($language);
    		}
    	}
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
