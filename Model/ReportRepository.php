<?php

/******************************************************************************/
// Class NewsRepository manage the entity News
// $newsList => array
/******************************************************************************/
class ReportRepository extends DBManager
{

	public function findAllReport() {
		$reportdata = $this->query("SELECT * from report ORDER BY date_report DESC;");
		$userrep = new UserRepository();
		$reportlist = array();
		foreach ($reportdata as $tuple) {
			$reportlist[] = new Report($tuple['fk_user_report'],$userrep->getUserPseudoById($tuple['fk_user_report']),$tuple['date_report'],$tuple['content_report']);
		}
		return $reportlist;
	}

	public function addReport(Report &$report) {
		$user = $report->getIduser();
		$pubdate = $report->getPubdate();
		$content = $report->getContent();

		$this->query("INSERT INTO report (fk_user_report, date_report, content_report) VALUES (?, ?, ?)",array($user, $pubdate, $content));
	}

	public function deleteAllReport() {
		$this->query("DELETE from report;");
	}

}