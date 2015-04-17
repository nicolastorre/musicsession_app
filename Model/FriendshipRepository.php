<?php

class FriendshipRepository extends DBManager
{

	public function getFriends($iduser) {
		$friends = array();
		$friendsdata = $this->query("SELECT * from friendship WHERE (fk_user_a = (?)) OR (fk_user_b = (?)) ORDER BY date_fdshp DESC;",array($iduser,$iduser));
		foreach ($friendsdata as $tuple) {
			$friends[] = new Friendship($tuple['fk_user_a'],$tuple['fk_user_b'],1,$tuple['date_fdshp']);
		}
		return $friends;
	}
	
	public function getFriendship($idusera,$iduserb) {
		$datefdshp = date("y-m-d H-i-s");
		if ($idusera != $iduserb) {
			$fdshpdata = $this->query("SELECT count(*) as fdshp from friendship WHERE (fk_user_a = (?) AND fk_user_b = (?)) OR (fk_user_a = (?) AND fk_user_b = (?));",array($idusera,$iduserb,$iduserb,$idusera));
			if ($fdshpdata[0]['fdshp']>0) {
				return new Friendship($idusera,$iduserb,1, $datefdshp);
			} else {
				return new Friendship($idusera,$iduserb,0, $datefdshp);
			}	
		} else {
			return new Friendship($idusera,$iduserb,-1, $datefdshp);
		}
		
	}

	public function update(Friendship $friendship) {
		$idusera = $friendship->getIdusera();
		$iduserb = $friendship->getIduserb();
		$status = $friendship->getStatus();
		$datefdshp = date("y-m-d H-i-s");
		if($status == 0) {
			$this->query("DELETE from friendship WHERE (fk_user_a = (?) AND fk_user_b = (?)) OR (fk_user_a = (?) AND fk_user_b = (?));",array($idusera,$iduserb,$iduserb,$idusera));
		} elseif ($status == 1) {
			$this->query("INSERT INTO friendship (fk_user_a ,fk_user_b, date_fdshp) VALUES (?, ?, ?);",array($idusera,$iduserb,$datefdshp));
		}
	}

	public function deleteAllFdUser($iduser) {
		$this->query("DELETE from friendship WHERE fk_user_a = (?) OR fk_user_b = (?);",array($iduser,$iduser));
	}
        
    public function suggestFriends($iduser) {
        $suggestfd = $this->query("SELECT id_user from user INNER JOIN likedtune on id_user = fk_user_lt INNER JOIN tune on fk_tune_lt = id_tune WHERE id_user NOT IN (SELECT fk_user_a from friendship WHERE fk_user_b = ?) AND id_user NOT IN (SELECT fk_user_b from friendship WHERE fk_user_a = ?) AND id_user != (?) AND access != 'Admin' AND category_tune IN (SELECT DISTINCT category_tune from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_lt = ?);",array($iduser,$iduser,$iduser,$iduser));
        if(count($suggestfd)<1) {
            return $this->query("SELECT id_user from user WHERE id_user NOT IN (SELECT fk_user_a from friendship WHERE fk_user_b = ?) AND id_user NOT IN (SELECT fk_user_b from friendship WHERE fk_user_a = ?) AND id_user != (?) AND access != 'Admin';",array($iduser,$iduser,$iduser));
        } else {
            return $suggestfd;
        }
    }
}

?>