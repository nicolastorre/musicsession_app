<?php

class Friendship
{
	private $idusera;
	private $iduserb;
	private $status;
	private $datefd;

	public function __construct($idusera, $iduserb, $status, $datefd) {
		$this->idusera = $idusera;
		$this->iduserb = $iduserb;
		$this->status = $status;
		$this->datefd = $datefd;
	}

	public function getIdusera() {
		return $this->idusera;
	}

	public function getIduserb() {
		return $this->iduserb;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getDate() {
		return $this->datefd;
	}

	public function setDate($status) {
		$this->datefd = $datefd;
	}

}

?>