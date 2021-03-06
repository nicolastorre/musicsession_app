<?php
// doc: http://swiftmailer.org/docs/sending.html

require_once 'swiftmailer-master/lib/swift_required.php';

class Mailer
{
	private $transport;
	private $mailer;

	public function __construct() {
		//Mail variable
		$smtp = ConfigReader::get("smtp");
		$port = intval(ConfigReader::get("port"));
		$protocol = ConfigReader::get("protocol");
		$login = ConfigReader::get("login");
		$pwd = ConfigReader::get("pwdmail");

		// Create the Transport
		$this->transport = Swift_SmtpTransport::newInstance($smtp, $port, $protocol)
		  ->setUsername($login)
		  ->setPassword($pwd)
		  ;

		/*
		You could alternatively use a different transport such as Sendmail or Mail:
		// Sendmail
		$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
		// Mail
		$transport = Swift_MailTransport::newInstance();
		*/
		// Create the Mailer using your created Transport
		$this->mailer = Swift_Mailer::newInstance($this->transport);
	}


	public function sendMail($subject, $sendermail, $senderid, $receivermail, $receiverid, $msg) {
		// Create a message
		$message = Swift_Message::newInstance($subject)
		  ->setFrom(array($sendermail => $senderid))
		  ->setTo(array($receivermail, $receivermail => $receiverid))
		  ->setBody($msg)
		  ->addPart($msg, 'text/html')
		  ;

		// Send the message
		$result = $this->mailer->send($message);
		if ($result === 1) {
			return true;
		} else{
			return false;
		}
	}


}

// $test = new Mailer();
// $test->sendmail("Music session", "musicsession@gmail.com", "Music Session App", "nico.torre.06@gmail.com", "Nico", "Hello \nThis is a test of Mailer Component!!!");


?>