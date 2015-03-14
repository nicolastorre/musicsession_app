<?php

class UserRepository  extends DBManager
{

	public function authUser($pseudo,$pwdhashed)	 {
		$userdata = $this->query("SELECT id_user, pseudo from user WHERE pseudo = (?) AND pwdhashed = (?);",array($pseudo,$pwdhashed));
		if (!empty($userdata)) {
			return $userdata[0];
		} else {
			return false;
		}
	}

	public function findUserById($iduser) {
		$userdata = $this->query("SELECT * from user WHERE id_user = (?) ;",array($iduser));
		if (!empty($userdata)) {
			return new User($userdata[0]['id_user'],$userdata[0]['pseudo'],$userdata[0]['pwdhashed'],$userdata[0]['firstname'],$userdata[0]['name'],$userdata[0]['email'],$userdata[0]['lang']);
		} else {
			$error = "findUserById => Not exist user";
			$e = new ErrorController($error);
			$e->indexAction();
			exit(1);
		}
	}

	public function getUserIdByPseudo($pseudo) {
		$userdata = $this->query("SELECT id_user from user WHERE pseudo = (?) ;",array($pseudo));
		if (!empty($userdata)) {
			return intval($userdata[0]['id_user']);
		} else {
			$error = "getUserIdByPseudo => Not exist user";
			$e = new ErrorController($error);
			$e->indexAction();
			exit(1);
		}
	}

	public function getUserPseudoById($iduser) {
		$userdata = $this->query("SELECT pseudo from user WHERE id_user = (?) ;",array($iduser));
		if (!empty($userdata)) {
			return $userdata[0]['pseudo'];
		} else {
			$error = "getUserPseudoById => Not exist user";
			$e = new ErrorController($error);
			$e->indexAction();
			exit(1);
		}
	}

	public function adduser(user $user) {		
		$pseudo = $user->getPseudo();
		$pwdhashed = $user->getPwdhashed();
		$firstname = $user->getFirstname();
		$name = $user->getName();
		$email = $user->getEmail();
		$lang = $user->getLang();
		$this->query("INSERT INTO user (pseudo, pwdhashed, firstname, name, email, lang) VALUES (?, ?, ?, ?, ?, ?)",array($pseudo, $pwdhashed, $firstname, $name, $email, $lang));
	}

	public function updateUser(user $user) {
		$iduser = $user->getIduser();
		$pseudo = $user->getPseudo();
		$pwdhashed = $user->getPwdhashed();
		$firstname = $user->getFirstname();
		$name = $user->getName();
		$email = $user->getEmail();
		$lang = $user->getLang();
		$this->query("UPDATE user SET pseudo = (?), pwdhashed = (?), firstname = (?), name = (?), email = (?), lang = (?) WHERE (id_user = (?));",array($pseudo, $pwdhashed, $firstname, $name, $email, $lang,$iduser));
	}

	public function allIdUser() {
		$userdata = $this->query("SELECT id_user from user;");
		$data = array();
		foreach ($userdata as $i) {
			$data[] = $i['id_user'];
		}
		return $data;
	}
}

?>