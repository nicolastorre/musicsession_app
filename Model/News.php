<?php

/******************************************************************************/
// Class ImeLineItem corresponding to the entity News with getters and setters methods
// 
/******************************************************************************/
class News
{
	protected $iduser;
	protected $userpseudo;
	protected $pubdate;
	private $content;

	public function __toString() {
		return $this->content;
	}

	public function __construct($iduser,$userpseudo,$pubdate,$content) {
		$this->iduser = $iduser;
		$this->userpseudo = $userpseudo;
		$this->pubdate = $pubdate;
		$this->content = $content;
	}

	public function getIduser() {
		return $this->iduser;
	}

	public function setIduser($iduser) {
		$this->iduser = $iduser;
	}
	public function getUserpseudo() {
		return $this->userpseudo;
	}

	public function setUserpseudo($userpseudo) {
		$this->userpseudo = $userpseudo;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getPubdate() {
		return $this->pubdate;
	}

	public function setPubdate($pubdate) {
		$this->pubdate = $pubdate;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

}