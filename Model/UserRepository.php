<?php

class UserRepository  extends DBManager
{

	public function authUser($pseudo,$pwdhashed)	 {
		$userdata = $this->query("SELECT id_user, pseudo, lang, access from user WHERE pseudo = (?) AND pwdhashed = (?);",array($pseudo,$pwdhashed));
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
			throw new Exception($error);
                    }
	}

	public function getUserIdByPseudo($pseudo) {
		$userdata = $this->query("SELECT id_user from user WHERE pseudo = (?) ;",array($pseudo));
		if (!empty($userdata)) {
			return intval($userdata[0]['id_user']);
		} else {
			$error = "getUserIdByPseudo => Not exist user";
			throw new Exception($error);
		}
	}
        
        public function existUserPseudo($pseudo) {
		$userdata = $this->query("SELECT count(id_user) as nb from user WHERE pseudo = (?) ;",array($pseudo));
		if (intval($userdata[0]['nb']) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getUserPseudoById($iduser) {
		$userdata = $this->query("SELECT pseudo from user WHERE id_user = (?) ;",array($iduser));
		if (!empty($userdata)) {
			return $userdata[0]['pseudo'];
		} else {
			$error = "getUserPseudoById => Not exist user";
			throw new Exception($error);
		}
	}

	public function adduser(user $user) {		
		$pseudo = $user->getPseudo();
		$pwdhashed = $user->getPwdhashed();
		$firstname = $user->getFirstname();
		$name = $user->getName();
		$email = $user->getEmail();
		$lang = $user->getLang();
		$key = $user->getKey();
		$this->query("INSERT INTO user (pseudo, pwdhashed, firstname, name, email, lang, key_user,confirmmail,access) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);",array($pseudo, $pwdhashed, $firstname, $name, $email, $lang,$key,0,"user"));
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
        
        public function searchUser($search) {
            $userdata = $this->query("SELECT pseudo from user WHERE pseudo LIKE (?);",array("%$search%"));
            $userpseudolist = array();
            if (!empty($userdata)) {
                    foreach($userdata as $user) {
                        $userpseudolist[] = $user["pseudo"];
                    }
                    return $userpseudolist;
            } else {
                    return false;
            }
        }

        public function confirmmail($key) {
        	if ($this->query("UPDATE user SET confirmmail = (?) WHERE (key_user = (?));",array(1, $key)) != null) {
        		return true;
        	} else {
        		return false;
        	}
        }
        
        public function deleteuser($iduser) {
            $result = true;
            if (is_null($this->query("DELETE from friendship WHERE fk_user_a = (?) OR fk_user_B = (?);",array($iduser,$iduser)))) {$result = false;}
            if (is_null($this->query("DELETE from likedtune WHERE fk_user_lt = (?);",array($iduser)))) {$result = false;}
            if (is_null($this->query("DELETE from message WHERE fk_sender = (?) OR fk_receiver = (?);",array($iduser,$iduser)))) {$result = false;}
            if (is_null($this->query("DELETE from news WHERE fk_user_news = (?);",array($iduser)))) {$result = false;}
            if (is_null($this->query("DELETE from score WHERE fk_user_score = (?);",array($iduser)))) {$result = false;}
            if (is_null($this->query("DELETE from tune WHERE fk_user_tune = (?);",array($iduser)))) {$result = false;}
            if($result) {
                if(!is_null($this->query("DELETE from user WHERE id_user = (?);",array($iduser)))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
}

?>