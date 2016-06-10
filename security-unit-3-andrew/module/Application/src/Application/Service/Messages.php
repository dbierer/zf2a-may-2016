<?php
namespace Application\Service;
use Application\Model\Message;

class Messages extends AbstractMessageService
{
    public function sendMessage(Message $message)
    {
        $this->someEvent()->trigger('message.send', $message);
        return $message;
    }
}
