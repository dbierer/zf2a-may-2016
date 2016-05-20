<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Application\Form\SwitchLanguage;
use Zend\View\ViewEvent;
use Zend\View\Renderer\PhpRenderer;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
		$em = $e->getApplication()->getEventManager();
		$sm = $e->getApplication()->getServiceManager();
		$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
		
		/** FROM Rob Allen - https://akrabat.com/configuring-a-zf2-view-helper-before-rendering/ **/
		$sem = $em->getSharedManager();
		$sem->attach('Zend\View\View', ViewEvent::EVENT_RENDERER_POST, function ($event) use ($sm) {
		    $language = $event->getRequest()->getQuery('language');
		    $renderer = $event->getRenderer();
		    if ($language && $renderer instanceof PhpRenderer) {
		        $languageList = $sm->get('languages');
		        if (in_array($language, array_keys($languageList), TRUE)) {
		            $sm->get('MvcTranslator')->setLocale($language);
		        }
		    }
		});
		/** NOTE
		 *  
		 *  The default translator instance in ZF2 (since ZF 2.2?) is a
		 *  Zend\Mvc\I18n\Translator instance.
		 *  
		 *  The Service Manager's MvcTranslator key maps to a
		 *  Zend\Mvc\Service\TranslatorServiceFactory,
		 *  which returns an instance of Zend\Mvc\I18n\Translator. The
		 *  MvcTranslator extends Zend\I18n\Translator\Translator and implements
		 *  Zend\Validator\Translator\TranslatorInterface, thus allowing
		 *  the instance to be used anywhere a translator may be required
		 *  in the framework.
		 *  
		 *  Therefore, if you decide to instantiate and use the 
		 *  Zend\I18n\Translator\Translator class, it will not be considered as the
		 *  application's default translator instance.  Thus, changing the latter's
		 *  properties in order to dynamically switch between language translations
		 *  will be useless, as the View Helper will still use 
		 *  Zend\Mvc\I18n\Translator's properties to determine its defaults.
		 *  
		 *  You should use
		 *  '$translator = $this->getServiceLocator()->get('MvcTranslator');'
		 *  in order to get the app's default translator and successfully modify
		 *  the values returned by all of the View Helper's calls to the
		 *  getTranslator() and getLocale() methods.
		 *  
		 *  http://framework.zend.com/manual/current/en/modules/zend.mvc.services.html
		 *  http://stackoverflow.com/questions/25019100/php-zf2-translator-language-switch
		 */
    }
    
    public function onDispatch(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$e->getViewModel()->setVariable('languageForm', $sm->get('application-switch-language-form'));
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
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'application-switch-language-form' => function ($sm) {
                    return new SwitchLanguage($sm->get('languages'));
                },
            ],
        ];
    }
}
