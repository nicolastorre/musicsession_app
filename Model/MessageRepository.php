<?php

class MessageRepository extends DBManager
{

	public function getDiscussion($idusera,$iduserb) {
		$discussion = array();
		$discussiondata = $this->query("SELECT * from message WHERE (fk_sender = (?) OR fk_sender = (?)) AND (fk_receiver = (?) OR fk_receiver = (?)) ORDER BY date_msg ASC;",array($idusera,$iduserb,$idusera,$iduserb));
		foreach ($discussiondata as $tuple) {
			$discussion[] = new Message($tuple['fk_sender'],$tuple['fk_receiver'],$tuple['date_msg'],$tuple['content_msg']);
		}
		return $discussion;
	}

	public function getLastDiscussion($idusera) {
		$discussion = array();
		$lastdiscu = $this->query("SELECT fk_sender, fk_receiver from message WHERE date_msg = (SELECT max(date_msg) FROM message WHERE (fk_sender = (?) OR fk_receiver = (?))) AND (fk_sender = (?) OR fk_receiver = (?))  ORDER BY date_msg DESC;",array($idusera,$idusera,$idusera,$idusera));
		if ($idusera != $lastdiscu[0]['fk_sender']) {
			$iduserb = $lastdiscu[0]['fk_sender'];
		} else {
			$iduserb = $lastdiscu[0]['fk_receiver'];
		}
		return $iduserb;
	}

	public function sendMsg(Message $msg) {
		$sender = $msg->getSender();
		$receiver = $msg->getReceiver();
		$date_msg = $msg->getDate();
		$content = $msg->getContent();

		$this->query("INSERT INTO message (fk_sender, fk_receiver, date_msg, content_msg) VALUES (?, ?, ?, ?)",array($sender, $receiver, $date_msg, $content));

	}
}

?>