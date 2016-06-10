<?php
include 'init_autoloader.php';

use Zend\Http\Client;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

// http://forums.zend.com/viewtopic.php?f=69&t=114593&p=220243&hilit=HttpClient#p220243
$client = new Client();
$client->setUri('http://forums.zend.com/viewtopic.php');
$client->setOptions(array('maxredirects' => 1, 'timeout' => 10, 'useragent' => 'My Cool Application'));
$client->setParameterGet(array('f' => '69', 't' => '114593', 'p' => '220243', 'hilit' => 'HttpClient#p220243'));
$result = $client->send();

echo $result->getBody();
