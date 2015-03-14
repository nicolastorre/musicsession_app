<?php

class TuneRepository extends DBManager
{
	
	public function addTune(Tune $tune) {
		$iduser = $tune->getIduser();
		$title = $tune->getTitle();
		$composer = $tune->getComposer();
		$category = $tune->getCategory();
		$datetune = $tune->getDatetune();
		$pdf = $tune->getPdf();
		$this->query("INSERT INTO tune (fk_user_tune, title_tune, composer, category_tune, date_tune, pdf_tune) VALUES (?, ?, ?, ?, ?, ?);",array($iduser, $title, $composer, $category, $datetune, $pdf));
		$idtune = $this->dblastInsertId();
		$this->query("INSERT INTO likedtune (fk_tune_lt, fk_user_lt) VALUES (?, ?)",array($idtune, $iduser));
	}


	public function FindUserLikedtune($iduser) {
		$tunedata = $this->query("SELECT * from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_tune = (?) ORDER BY date_tune DESC;",array($iduser));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[] =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
		}
		return $tunelist;
	}

}

?>