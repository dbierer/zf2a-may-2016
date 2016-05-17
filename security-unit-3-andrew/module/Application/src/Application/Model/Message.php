<?php
namespace Application\Model;
class Message
{
	protected $text;
	protected $sender;
	protected $recipient;

	public function __construct($recipient = null, $sender = null, $text = null)
	{
		$this->recipient = $recipient;
		$this->sender = $sender;
		$this->text = $text;
	}
	
	/**
	 * @return the $text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return the $sender
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * @return the $recipient
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * @param field_type $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @param field_type $sender
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}

	/**
	 * @param field_type $recipient
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

}
