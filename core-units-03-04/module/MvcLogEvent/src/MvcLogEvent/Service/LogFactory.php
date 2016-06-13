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

use Zend\Log;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // get params
        $params = $serviceLocator->get('params');

        $writer = new Log\Writer\Stream($params['log']);
        $formatter = new Log\Formatter\Simple('%timestamp% | %message%');
        $writer->setFormatter($formatter);
        $logger = new Log\Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}
