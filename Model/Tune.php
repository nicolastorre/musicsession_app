<?php

class Tune
{
	private $idtune;
	private $iduser;
	private $title;
	private $composer;
	private $category;
	private $datetune;
	private $pdf;
	private $forkedusers;
	private $forkedpdf;

	public function __construct($idtune, $iduser, $title, $composer, $category, $datetune, $pdf) {
		$this->idtune = $idtune;
		$this->iduser = $iduser;
		$this->title = $title;
		$this->composer = $composer;
		$this->category = $category;
		$this->datetune = $datetune;
		$this->pdf = $pdf;

	}

	public function getIdtune() {
		return $this->idtune;
	}

	public function getIduser() {
		return $this->iduser;
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

	public function getPdf() {
		return $this->pdf;
	}
}

?>