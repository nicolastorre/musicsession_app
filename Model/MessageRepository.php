<?php

class MessageRepository extends DBManager
{

	public function getDiscussion($idusera,$iduserb) {
		$discussion = array();
		$discussiondata = $this->query("SELECT * from message WHERE (fk_sender = (?) OR fk_sender = (?)) AND (fk_receiver = (?) OR fk_receiver = (?)) ORDER BY date_msg ASC;",array($idusera,$iduserb,$idusera,$iduserb));
		foreach ($discussiondata as $tuple) {
			$discussion[] = new Message($tuple['id_msg'],$tuple['fk_sender'],$tuple['fk_receiver'],$tuple['date_msg'],$tuple['content_msg']);
		}
		return $discussion;
	}

	public function getLastDiscussion($idusera) {
		$discussion = array();
		$lastdiscu = $this->query("SELECT fk_sender, fk_receiver from message WHERE date_msg = (SELECT max(date_msg) FROM message WHERE (fk_sender = (?) OR fk_receiver = (?))) AND (fk_sender = (?) OR fk_receiver = (?))  ORDER BY date_msg DESC;",array($idusera,$idusera,$idusera,$idusera));
		if ($lastdiscu != null) {
			if ($idusera != $lastdiscu[0]['fk_sender']) {
				$iduserb = $lastdiscu[0]['fk_sender'];
			} else {
				$iduserb = $lastdiscu[0]['fk_receiver'];
			}
			return $iduserb;
		} else {
			return false;
		}
	}

	public function sendMsg(Message $msg) {
		$sender = $msg->getSender();
		$receiver = $msg->getReceiver();
		$date_msg = $msg->getDate();
		$content = $msg->getContent();

		$this->query("INSERT INTO message (fk_sender, fk_receiver, date_msg, content_msg, readd) VALUES (?, ?, ?, ?, ?)",array($sender, $receiver, $date_msg, $content, 0));

	}

	public function getLastMessage($idusera, $iduserb, $lastmsg) {
		$discussion = array();
		$discussiondata = $this->query("SELECT * from message WHERE (fk_sender = (?) OR fk_sender = (?)) AND (fk_receiver = (?) OR fk_receiver = (?)) AND (id_msg > ?) ORDER BY date_msg ASC;",array($idusera,$iduserb,$idusera,$iduserb,$lastmsg));
		foreach ($discussiondata as $tuple) {
			$discussion[] = new Message($tuple['id_msg'],$tuple['fk_sender'],$tuple['fk_receiver'],$tuple['date_msg'],$tuple['content_msg']);
		}
		return $discussion;
	}

	public function readMessages($idusera,$iduserb) {
		$this->query("UPDATE message SET readd = (?) WHERE fk_sender = (?) AND fk_receiver = (?);",array(1,$idusera,$iduserb));
	}

	public function getNonReadMessages($iduser) {
		$readdata = $this->query("SELECT count(id_msg) as nb from message WHERE readd = 0 AND fk_receiver = (?);",array($iduser));
		return $readdata[0];
	}

	public function getNonReadMessagesByFriend($iduser,$idfriend) {
		$readdata = $this->query("SELECT count(id_msg) as nb from message WHERE readd = 0 AND fk_sender = (?) AND fk_receiver = (?);",array($idfriend,$iduser));
		return $readdata[0];
	}
}

?>