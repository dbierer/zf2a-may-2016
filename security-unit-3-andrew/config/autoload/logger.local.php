<?php
use Zend\Log;

return array(
    'service_manager' => array(
        'services' => array(
            'email-info' => array(
                'dir'		=> realpath(__DIR__ . '/../../data/email'),
            ),
        ),
        'factories' => array(
            'invalid-login-logger' 	=> function ($services) {
                $logFile = realpath(__DIR__ . '/../../data/logs') . '/invalid_attempts.log';
                $writer = new Log\Writer\Stream($logFile);
                $formatter = new Log\Formatter\Simple('Invalid Attempt By: %message% : %timestamp%');
                $writer->setFormatter($formatter);
                $logger = new Log\Logger();
                $logger->addWriter($writer);
                return $logger;
            }
        ),
    ),
);
