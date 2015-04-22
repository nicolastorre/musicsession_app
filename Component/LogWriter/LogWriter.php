<?php

class LogWriter {
	# @string, Log directory name
	private $path = 'Config/';
	# @string, Log dfile name
	private $file = 'error.xml';

	# @void, Default Constructor, Sets the timezone and path of the log files.
	public function __construct() {
		date_default_timezone_set('Europe/Amsterdam');
	}

	public function writeLog($message) {
		$date = new DateTime();
		$log = $this->path . $this->file;
		if(is_dir($this->path)) {
			// $fh = fopen($log, 'a+') or die("Fatal Error !");
			// $logcontent = $date->format('d/m/y H:i:s')."\t" . $message ."\r\n";
			// fwrite($fh, $logcontent);
			// fclose($fh);

			$err = "<errorentry>\n";
            $err .= "\t<datetime>" . $date->format('d/m/y H:i:s') . "</datetime>\n";
            $err .= "\t<errormsg>" . $message . "</errormsg>\n";
            $err .= "</errorentry>\n\n";
            error_log($err, 3, $this->path.$this->file);
		}
		else {}
	}

	public function mailLog() {
		return 1;
	}
}

?>