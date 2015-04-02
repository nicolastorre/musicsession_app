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
		$tunedata = $this->query("SELECT * from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_lt = (?) ORDER BY date_tune DESC;",array($iduser));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[] =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
		}
		return $tunelist;
	}

	public function FindUserLikedtuneGroupByCategory($iduser) {
		$tunedata = $this->query("SELECT * from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_lt = (?) ORDER BY date_tune DESC;",array($iduser));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[$tuple['category_tune']][] =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
		}
		return $tunelist;
	}

	public function FindLastTune($nlast) {
		$tunedata = $this->query("SELECT * FROM tune ORDER BY date_tune DESC LIMIT 0,?;",array($nlast));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[] =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
		}
		return $tunelist;
	}

	

	public function checkTuneLikedByUser($iduser, $idtune) {
		$tunedata = $this->query("SELECT count(*) as nbtune from likedtune  WHERE fk_user_lt = (?) AND fk_tune_lt = (?);",array($iduser, $idtune));
		if ($tunedata[0]['nbtune'] > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function findTuneById($idtune) {
		$tunedata = $this->query("SELECT * from tune WHERE id_tune = (?) limit 1;",array($idtune));
                if (!empty($tunedata)) {
                    $tuple = $tunedata[0];
                    $tune =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
                    return $tune;
                } else {
                    return false;
                }
	}

	public function deleteTuneForUser($iduser, $idtune) {
		return $this->query("DELETE from likedtune  WHERE fk_user_lt = (?) AND fk_tune_lt = (?);",array($iduser, $idtune));
	}
        
        public function deleteTuneForAll($idtune) {
                $this->query("DELETE from likedtune  WHERE fk_tune_lt = (?);",array($idtune));
                return $this->query("DELETE from tune  WHERE id_tune = (?);",array($idtune));      
	}

	public function shareTune($iduser, $idtune) {
		return $this->query("INSERT INTO likedtune  (fk_tune_lt, fk_user_lt) VALUES (?, ?);",array($idtune, $iduser));
	}

}

?>