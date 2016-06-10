<?php
namespace HootAndHoller\Service;
use Zend\EventManager\EventManager;
abstract class AbstractMessageService
{
    protected $eventManager;
    public function someEvent()
    {
        if (!$this->eventManager instanceof EventManager) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }
}
