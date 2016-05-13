<?php
/**
 * MvcLogEvent Module
 *
 * @package MvcLogEvent Module
 * @author Andrew Caya
 * @link https://github.com/andrewscaya
 * @version 1.0.0
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace MvcLogEvent\Service;

use Zend\EventManager\Event;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

class MvcLogEvent extends Event
{
	
    public function logEvent($controllerActionName, $action, $item, ServiceManager $serviceManager)
    {	    
        $logger = $serviceManager->get('log');
        
        switch ($action) {
            case "add":
               $logger->info('Added new post "' . $item['title'] . '"');
               break;
            // Add other cases as needed
            default:
                $logger->warn('ERROR: ' . $controllerActionName . ': Bad request to the logger.');
        }
    }

}
