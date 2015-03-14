<?php

class LogWriter {
	# @string, Log directory name
	private $path = 'Config/';
	# @string, Log dfile name
	private $file = 'log.txt';

	# @void, Default Constructor, Sets the timezone and path of the log files.
	public function __construct() {
		date_default_timezone_set('Europe/Amsterdam');
	}

	public function writeLog($message) {
		$date = new DateTime();
		$log = $this->path . $this->file;
		if(is_dir($this->path)) {
			$fh = fopen($log, 'a+') or die("Fatal Error !");
			$logcontent = $date->format('d/m/y H:i:s')."\t" . $message ."\r\n";
			fwrite($fh, $logcontent);
			fclose($fh);
		}
		else {}
	}

	public function mailLog() {
		return 1;
	}
}

?>