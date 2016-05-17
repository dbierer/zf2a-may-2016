<?php
namespace HootAndHoller\Service;
use HootAndHoller\Model\Message;

class Messages extends AbstractMessageService
{
	public function sendMessage(Message $message)
	{
		$this->someEvent()->trigger('message.send', $message);
		return true;
	}	
}
