<?php

class User
{
	private $id_user;
	private $pseudo;
	private $pwdhashed;
	private $firstname;
	private $name;
	private $email;
	private $lang;
	private $key;

	public function __construct($iduser,$pseudo,$pwdhashed,$firstname,$name,$email,$lang,$key = null) {
		$this->id_user = $iduser;
		$this->pseudo = $pseudo;
		$this->pwdhashed = $pwdhashed;
		$this->firstname = $firstname;
		$this->name = $name;
		$this->email = $email;
		$this->lang = $lang;
		$this->key = $key;
	}

	public function getIduser() {
		return $this->id_user;
	}

	public function getPseudo() {
		return $this->pseudo;
	}

	public function setpseudo($pseudo) {
		$this->pseudo = $pseudo;
	}

	public function getPwdhashed() {
		return $this->pwdhashed;
	}

	public function setPwdhashed($pwd) {
		return $this->pwdhashed = $pwd;
	}

	public function getFirstname() {
		return $this->firstname;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		return $this->email = $email;
	}

	public function getLang() {
		return $this->lang;
	}

	public function setLang($lang) {
		return $this->lang = $lang;
	}

	public function getKey() {
		return $this->key;
	}

	public function setKey() {
		$this->key = $key;
	}
}

?>