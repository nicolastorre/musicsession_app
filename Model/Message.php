<?php

class Message
{
    private $idmsg;
	private $sender;
	private $receiver;
	private $date;
	private $content;

	public function __construct($idmsg, $sender, $receiver, $date, $content) {
        $this->idmsg = $idmsg;
		$this->sender = $sender;
		$this->receiver = $receiver;
		$this->date = $date;
		$this->content = $content;
	}
        
        public function getIdmsg() {
		return $this->idmsg;
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