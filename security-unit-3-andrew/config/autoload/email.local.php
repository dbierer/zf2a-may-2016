<?php
use Zend\Mail\Transport;
return array(
    'service_manager' => array(
        'services' => array(
            'email-info' => array(
                'dir'		=> realpath(__DIR__ . '/../../data/email'),
            ),
        ),
        'factories' => array(
            'mail-transport' 	=> function ($services) {
                $emailInfo = $services->get('email-info');
                // file transport is used for testing and development
                $transport = new Transport\File(new Transport\FileOptions(array('path' => $emailInfo['dir'])));
                // uncomment line below for production
                //$transport = new Transport\Sendmail();
                return $transport;
            }
        ),
    ),
);
