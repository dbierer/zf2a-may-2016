<?php
/**
 * MvcLogEvent Module
 *
 * @package MvcLogEvent Module
 * @author Andrew Caya
 * @link https://github.com/andrewscaya
 * @version 2.0.0
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace MvcLogEvent;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use MvcLogEvent\Service\MvcLogEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Get instance of the SEM
        $sharedEventManager = $eventManager->getSharedManager();
        // Create main log triggering event
        $sharedEventManager->attach(
            'MvcLogEventModule',
            'triggerMvcLogEvent',
            function ($mvcLogEvent) {
                $mvcLogEvent->logEvent();
            },
            1);
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
