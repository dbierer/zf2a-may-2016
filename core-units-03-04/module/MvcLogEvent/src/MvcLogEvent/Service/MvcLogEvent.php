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

namespace MvcLogEvent\Service;

use Zend\EventManager\Event;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

class MvcLogEvent extends Event
{
    /**
     * Log the event
     *
     * @param NULL
     * @return boolean
     */
    public function logEvent()
    {
        // Core logic
        $serviceManager = $this->getTarget()->getServiceLocator()->get('ServiceManager');
        $logger = $serviceManager->get('log');
        $eventManager = $this->getTarget()->getEventManager();
        $eventManager->setIdentifiers('MvcLogEventModule');
        $eventManager->trigger(__FUNCTION__, $this);
        
        // Optional parameters
        $controllerActionName = $this->getParam('controllerActionName');
        $action = $this->getParam('action');
        $item = $this->getParam('item');
        
        switch ($action) {
            case "add":
                $logger->info('Added new post "' . $item['title'] . '"');
                break;
            // Add other cases as needed
            default:
                $eventManager->trigger(__FUNCTION__ . '.error', $this);
                $logger->warn('ERROR: ' . $controllerActionName . ': Bad request to the logger.');
                return FALSE;
        }
        
        $eventManager->trigger(__FUNCTION__ . '.post', $this);
        
        return TRUE;
    }

}
