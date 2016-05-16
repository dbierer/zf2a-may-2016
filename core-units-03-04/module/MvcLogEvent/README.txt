MvcLogEvent Module README

To install the MvcLogEvent Module, you must copy it to the ZF2 module folder,
activate the module in config/application.config.php and use it in your
controllers.  Here is an example of how you can use it in a controller:

[...]

// Log event
$em->setEventClass('MvcLogEvent\Service\MvcLogEvent');
$em->setIdentifiers('MvcLogEventModule');
$em->trigger(
    'triggerMvcLogEvent',
    $this,
    [
        'controllerActionName' => __CLASS__ . '\\' . __FUNCTION__,
        'action' => 'add',
        'item' => $validData,
    ]);
    
[...]

All parameters are optional and you can therefore modify the logic behind
MvcLogEvent\Service\MvcLogEvent's logEvent() method to fit your needs.

Have a lot of fun!

Andrew Caya