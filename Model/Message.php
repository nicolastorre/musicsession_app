<?php

class Message
{
	private $sender;
	private $receiver;
	private $date;
	private $content;

	public function __construct($sender, $receiver, $date, $content) {
		$this->sender = $sender;
		$this->receiver = $receiver;
		$this->date = $date;
		$this->content = $content;
	}

	public function getSender() {
		return $this->sender;
	}

	public function getReceiver() {
		return $this->receiver;
	}

	public function getDate() {
		return $this->date;
	}

	public function getContent() {
		return $this->content;
	}
}

?>