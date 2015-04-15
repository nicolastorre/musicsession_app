<?php

class Tune
{
	private $idtune;
	private $title;
	private $composer;
	private $category;
	private $datetune;
	private $forkedusers;
	private $pdfscore;
    private $datescore;
    private $idscorelist;

	public function __construct($idtune, $title, $composer, $category, $datetune, $pdfscore = "", $forkedusers = "", $datescore = "",$idscorelist = "") {
		$this->idtune = $idtune;
		$this->title = $title;
		$this->composer = $composer;
		$this->category = $category;
		$this->datetune = $datetune;
        $this->forkedusers = $forkedusers;
        $this->pdfscore = $pdfscore;
        $this->datescore = $datescore;
        $this->idscorelist = $idscorelist;
	}

	public function getIdtune() {
		return $this->idtune;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getComposer() {
		return $this->composer;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getDatetune() {
		return $this->datetune;
	}
        
    public function getForkedusers() {
            return $this->forkedusers;
    }
    
    public function getPdfscore() {
            return $this->pdfscore;
    }
    
    public function getDatescore() {
            return $this->datescore;
    }
    
    public function getIdscorelist() {
            return $this->idscorelist;
    }
}

?>