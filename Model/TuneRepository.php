<?php

class TuneRepository extends DBManager
{
	
	public function addTune(Tune $tune) {
		$title = $tune->getTitle();
		$composer = $tune->getComposer();
		$category = $tune->getCategory();
		$datetune = $tune->getDatetune();
        $pdfscore = $tune->getPdfscore();
        $iduser = $tune->getForkedusers();
		$this->query("INSERT INTO tune (title_tune, composer, category_tune, date_tune) VALUES (?, ?, ?, ?);",array($title, $composer, $category, $datetune));
		$idtune = $this->dblastInsertId();
		$this->query("INSERT INTO likedtune (fk_tune_lt, fk_user_lt) VALUES (?, ?);",array($idtune, $iduser));
        $this->query("INSERT INTO score (fk_tune_score, fk_user_score, pdf_score, date_score) VALUES (?, ?, ?, ?);",array($idtune, $iduser, $pdfscore, $datetune));
	}
        
        public function addScore(Tune $tune) {
                $idtune = $tune->getIdtune();
                $iduser = $tune->getForkedusers();
		        $datetune = $tune->getDatetune();
                $pdfscore = $tune->getPdfscore();
                $this->query("INSERT INTO score (fk_tune_score, fk_user_score, pdf_score, date_score) VALUES (?, ?, ?, ?);",array($idtune, $iduser, $pdfscore, $datetune));
	}
        
        public function getScorePdfName($idscore) {
            $scoredata = $this->query("SELECT pdf_score from score WHERE id_score = (?) limit 1;",array($idscore));
            if (!empty($scoredata)) {
                return $scoredata[0]['pdf_score'];
            } else {
                return false;
            }
        }
        
        public function getScoreUser($idscore) {
            $scoredata = $this->query("SELECT fk_user_score from score WHERE id_score = (?) limit 1;",array($idscore));
            if (!empty($scoredata)) {
                return $scoredata[0]['fk_user_score'];
            } else {
                return false;
            }
        }


	public function FindUserLikedtune($iduser) {
		$tunedata = $this->query("SELECT * from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_lt = (?) ORDER BY date_tune DESC;",array($iduser));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[] =  new Tune($tuple['id_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune']);
		}
		return $tunelist;
	}

	public function FindUserLikedtuneGroupByCategory($iduser) {
		$tunedata = $this->query("SELECT * from tune INNER JOIN likedtune on id_tune = fk_tune_lt WHERE fk_user_lt = (?) ORDER BY date_tune DESC;",array($iduser));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[$tuple['category_tune']][] =  new Tune($tuple['id_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune']);
		}
		return $tunelist;
	}

	public function FindLastTune($nlast) {
		$tunedata = $this->query("SELECT * FROM tune ORDER BY date_tune DESC LIMIT 0,?;",array($nlast));
		$tunelist = array();
		foreach ($tunedata as $tuple) {
			$tunelist[] =  new Tune($tuple['id_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune']);
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

	/*public function findTuneById($idtune) {
		$tunedata = $this->query("SELECT * from tune WHERE id_tune = (?) limit 1;",array($idtune));
                if (!empty($tunedata)) {
                    $tuple = $tunedata[0];
                    $tune =  new Tune($tuple['id_tune'],$tuple['fk_user_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$tuple['pdf_tune']);
                    return $tune;
                } else {
                    return false;
                }
	}*/
        
        public function findTuneById($idtune) {
		$tunedata = $this->query("SELECT * from tune WHERE id_tune = (?) limit 1;",array($idtune));
                $scoredata = $this->query("SELECT * from score WHERE fk_tune_score = (?) ORDER BY date_score;",array($idtune));
                if (!empty($tunedata)) {
                    $tuple = $tunedata[0];
                    if (!empty($scoredata)) {
                        $datescore = array();
                        $forkedusers = array();
                        $pdfscore = array();
                        $idscorelist = array();
                        foreach ($scoredata as $i) {
                            $datescore[] = $i['date_score'];
                            $forkedusers[] = $i['fk_user_score'];
                            $pdfscore[] = $i['pdf_score'];
                            $idscorelist[] = $i['id_score'];
                        }
                    } else {
                        $datescore = array("");
                        $forkedusers = array("");
                        $pdfscore = array("");
                        $idscorelist = array("");
                    }
                    $tune =  new Tune($tuple['id_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune'],$pdfscore,$forkedusers,$datescore,$idscorelist);
                    return $tune;
                } else {
                    return false;
                }
	}

	public function findTuneByTitle($title) {
            $tunedata = $this->query("SELECT * from tune WHERE title_tune = (?) limit 1;",array($title));
            if (!empty($tunedata)) {
                $tuple = $tunedata[0];
                $tune =  new Tune($tuple['id_tune'],$tuple['title_tune'],$tuple['composer'],$tuple['category_tune'],$tuple['date_tune']);
                return $tune;
            } else {
                return false;
            }
	}
        
        public function findTitleTuneById($idtune) {
                $tunedata = $this->query("SELECT title_tune from tune WHERE id_tune = (?) limit 1;",array($idtune));
                if (!empty($tunedata)) {
                    return $tunedata[0]['title_tune'];
                } else {
                    return false;
                }
        }

        public function isTitleTuneuniq($titletune) {
                $tunedata = $this->query("SELECT count(*) as nb from tune WHERE title_tune = (?);",array($titletune));
                if ($tunedata[0]['nb'] > 0) {
                    return false;
                } else {
                    return true;
                }
        }

        public function nbLikedTune($idtune) {
            $result = $this->query("SELECT COUNT(*) as nb from likedtune WHERE fk_tune_lt = (?);",array($idtune));
            return $result[0]['nb'];
        }

        public function deleteTuneForUser($iduser, $idtune) {
                return $this->query("DELETE from likedtune  WHERE fk_user_lt = (?) AND fk_tune_lt = (?);",array($iduser, $idtune));
        }
        
        public function deleteScore($idscore) {
                $this->query("DELETE from score WHERE id_score = (?);",array($idscore));
        }

        public function deleteScoreTuneByUser($idtune,$iduser) {
                $this->query("DELETE from score WHERE fk_tune_score = (?) AND fk_user_score = (?);",array($idtune,$iduser));
        }

        public function deleteTuneForAll($idtune) {
                $this->query("DELETE from tune  WHERE id_tune = (?);",array($idtune));           
        }

        public function deleteAllTuneUser($iduser) {
                $this->query("DELETE from likedtune  WHERE fk_user_lt = (?);",array($iduser));
                $idtunelist = $this->query("SELECT fk_tune_score from score WHERE fk_user_score = (?);",array($iduser));
                foreach ($idtunelist as $tuple) {
                    if($this->nbLikedTune($tuple['fk_tune_score']) == 0) {
                        $this->query("DELETE from score WHERE fk_user_score = (?);",array($iduser));
                        $this->deleteTuneForAll($tuple['fk_tune_score']);
                    } else {
                        $this->query("DELETE from score WHERE fk_user_score = (?);",array($iduser));
                    }
                }
        }

	public function shareTune($iduser, $idtune) {
		return $this->query("INSERT INTO likedtune  (fk_tune_lt, fk_user_lt) VALUES (?, ?);",array($idtune, $iduser));
	}

}

?>