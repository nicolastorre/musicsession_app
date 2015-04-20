<?php

class InvitationRepository  extends DBManager
{
	public function getNbInvitation($idusera,$iduserb) {
		$invitationcount = $this->query("SELECT count(*) as nb from invitation WHERE (fk_user_a = (?) AND fk_user_b = (?)) OR (fk_user_a = (?) AND fk_user_b = (?));",array($idusera,$iduserb,$iduserb,$idusera));
		return $invitationcount[0]['nb'];
	}

	public function getAllInvitation($iduser) {
		$invitationlist = array();
		$invitationdata = $this->query("SELECT * from invitation WHERE fk_user_a = (?);",array($iduser));
		if(!empty($invitationdata)) {
			foreach($invitationdata as $tuple) {
				$invitationlist[] = new Invitation($tuple['fk_user_a'],$tuple['fk_user_b'],0,$tuple['date_fdshp']);
			}
			return $invitationlist;
		} else {
			return null;
		}
	}

	public function addInvitation(Invitation $invitation) {
		$idusera = $invitation->getIdusera();
		$iduserb = $invitation->getIduserb();
		$status = $invitation->getStatus();
		$datefdshp = date("y-m-d H-i-s");
		if($this->getNbInvitation($idusera,$iduserb) == 0) {
			$this->query("INSERT INTO invitation (fk_user_a ,fk_user_b, date_fdshp, readd) VALUES (?, ?, ?, 0);",array($idusera,$iduserb,$datefdshp));
		} else {
			return 2;
		}
	}

	public function deleteInvitation($idusera, $iduserb) {
		$this->query("DELETE from invitation WHERE fk_user_a = (?) and fk_user_b = (?);",array($idusera,$iduserb));
	}

	public function readInvitation($idusera,$iduserb) {
		$this->query("UPDATE invitation SET readd = (?) WHERE fk_user_a = (?) AND fk_user_b = (?);",array(1,$idusera,$iduserb));
	}

	public function getNonReadInvitation($iduser) {
		$readdata = $this->query("SELECT count(id_fdshp) as nb from invitation WHERE readd = 0 AND fk_user_a = (?);",array($iduser));
		return $readdata[0];
	}
}

?>