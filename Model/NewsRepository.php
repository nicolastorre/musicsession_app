<?php

/******************************************************************************/
// Class NewsRepository manage the entity News
// $newsList => array
/******************************************************************************/
class NewsRepository extends DBManager
{
	// private $newsList = array();

	//CONSTRUCTOR
	public function __construct() {
	}

	// return in an array all news from the DB order by publication date descending
	public function findAllNewsFriendsUser($iduser) {
		$newsdata = $this->query("SELECT * from news INNER JOIN user on (fk_user_news = id_user) WHERE id_user = (?) OR id_user IN (SELECT fk_user_b from friendship WHERE fk_user_a = (?)) OR id_user IN (SELECT fk_user_a from friendship WHERE fk_user_b = (?)) ORDER BY date_news DESC;",array($iduser,$iduser,$iduser));
		$newslist = array();
		foreach ($newsdata as $tuple) {
			$newslist[] = new News($tuple['id_user'],$tuple['pseudo'],$tuple['date_news'],$tuple['content_news']);
		}
		return $newslist;
	}

	public function findAllNewsUser($iduser) {
		$newsdata = $this->query("SELECT * from news INNER JOIN user on (fk_user_news = id_user) WHERE id_user = (?) ORDER BY date_news DESC;",array($iduser));
		$newslist = array();
		foreach ($newsdata as $tuple) {
			$newslist[] = new News($tuple['id_user'],$tuple['pseudo'],$tuple['date_news'],$tuple['content_news']);
		}
		return $newslist;
	}

	public function addNews(News &$news) {
		$user = $news->getIduser();
		$pubdate = $news->getPubdate();
		$content = $news->getContent();

		$this->query("INSERT INTO news (fk_user_news, date_news, content_news) VALUES (?, ?, ?)",array($user, $pubdate, $content));
	}

	public function deleteAllNewsUser($iduser) {
		$this->query("DELETE from news WHERE fk_user_news = (?);",array($iduser));
	}

}