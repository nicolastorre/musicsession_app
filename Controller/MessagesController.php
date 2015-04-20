<?php
/**
 * File containing the MessagesController Class
 *
 */

/**
 * MessagesController
 *
 * MessageseController class display a list of discussion and the messages of the last discussion or the selected discussion
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class MessagesController extends BaseController
{
	/**
    * Send number of non-read messages via AJAX
    *
    * @return void .
    */
	public function readerAction() {
		$iduser = $_SESSION['iduser'];
		$msgrep = new MessageRepository();
		$readd = $msgrep->getNonReadMessages($iduser);
		header('Content-type: application/json');
        echo json_encode($readd);
	}

	/**
    * Search for hashtag in sending message 
    * if a word (without whitespace) is preceding by # so the method will check if a tune with this word exist
    * then replace the hashtag by a link to the tune
    *
    * @param Request &$request Request object.
    * @param String $msg any sending message.
    * @return void .
    */
    public static function replaceHashtag($msg) {
        $matches = array();
        $toFind = "#";
        $start = 0;
        do { 
        	$pos = strpos(($msg),$toFind,$start);
            $end = strpos($msg," ",($pos+1));
            if ($end == false) {
                $end = strlen($msg);
            }
            $length = $end - $pos;
            $matches[] = substr($msg, $pos, $length);
            $start = $pos + 1;
        } while (($pos != false));

        $replacement = array();
        $tunerep = new TuneRepository();
        for ($i=0; $i<count($matches); $i++) {
        	$tune = $tunerep->findTuneByTitle(substr($matches[$i],1));

        	if ($tune != false) {
        		$idtune = $tune->getIdtune();
        		$url = UrlRewriting::generateURL("Tune", $_SESSION['pseudo']."/".$idtune);
            	$replacement[$i] = "<a href='".$url."' class='hashtag'>".$matches[$i]."</a>";
        	} else {
        		$replacement[$i] = $matches[$i];
        	}
        }
        return str_replace($matches, $replacement, $msg);
    }

	/**
    * Get and display the messages corresponding to the selected discussion
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function getdiscussionAction(Request &$request) {
		$userrep = new UserRepository();
		$iduserb = $userrep->getUserIdByPseudo($request->getParameter("par")[0]);
		$this->indexAction($request, $f = null, $iduserb);
		ProfilController::checkAllowedProfileUser($request, $iduserb);  // protection user profile
	}

	/**
    * Send the message 
    * if the form is valid => save the message in the DB
    * display the messages of the current discussion
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function sendmsgAction(Request &$request) {
		if(!isset($_SESSION["sendmsgform"])) throw new Exception("\$_SESSION['sendmsgform'] doesn't exist!");

		$f = unserialize($_SESSION["sendmsgform"]);
		$userrep = new UserRepository();
		$iduserb = $userrep->getUserIdByPseudo($request->getParameter("par")[0]);
		if ($f->validate($request)) {
                        unset($_SESSION["sendmsgform"]);
                        
			$dataform = $f->getValues();
			$dataform['msg'] = self::replaceHashtag($dataform['msg']);

			$date_msg = date("y-m-d H-i-s");
			$msg = new Message(null,$_SESSION['iduser'],$iduserb,$date_msg,$dataform['msg']);
			$msgrep = new MessageRepository();
			$msgrep->sendMsg($msg);
			$this->indexAction($request, $f = null, $iduserb);

		} else {
			$this->indexAction($request, $f);
		}
	}

	/**
    * Load the newest messages using AJAX
    * the script use the $iduser to get the selected discussion and
    * $lastmsg to get the messages from this last message
    * the script send the results in JSON format to be get by the JS script
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function loaderAction(Request &$request) {
    	$iduser = intval($request->getParameter("par")[0]);
    	$lastmsg = intval($request->getParameter("par")[1]);

    	$userrep = new UserRepository();
    	$msgrep = new MessageRepository();
    	$discussion = $msgrep->getLastMessage($_SESSION['iduser'], $iduser, $lastmsg);

    	$msglist = array(); // list of message of the current discussion
		if (!empty($discussion)) {
			foreach ($discussion as $msg) {
				$msglist[] = array("idmsg" => $msg->getIdmsg(),"url" => UrlRewriting::generateURL("Profil",$userrep->getUserPseudoById($msg->getSender())), 
					"pseudo" => $userrep->getUserPseudoById($msg->getSender()),
					"profilephoto" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($msg->getSender()),"profile_pic.png", "../default/profile_pic.png"),
					"date" => Pubdate::printDate($msg->getDate()), 
					"content" => $msg->getContent());
			}    
		}
        header('Content-type: application/json');
        echo json_encode($msglist);
    }

	/**
    * Send the message using AJAX
    * if the form is valid => save the message in the DB
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function sendmsgajaxAction(Request &$request) {
    	if(!isset($_SESSION["sendmsgform"])) throw new Exception("\$_SESSION['sendmsgform'] doesn't exist!");

		$f = unserialize($_SESSION["sendmsgform"]);
		$userrep = new UserRepository();
		$iduserb = intval($request->getParameter("par")[0]);
		if ($f->validate($request)) {
			$dataform = $f->getValues();
			$date_msg = date("y-m-d H-i-s");
                        
            $dataform['msg'] = self::replaceHashtag($dataform['msg']);
                        
			$msg = new Message(null,$_SESSION['iduser'],$iduserb,$date_msg,$dataform['msg']);
			$msgrep = new MessageRepository();
			$msgrep->sendMsg($msg);
		} 
	}
	 
	/**
    * Create the default messages page view
    *
    * Display a timeline of the current discussion, the list of user's friends to start a discussion, 
    * the Profile Card module and the Suggested friends module
    *
    * @param Request &$request Request object.
    * @param FormManager $f optional. contain a form object, textarea to send message with submit button, default is null.
    * @param int $iduserb optional. id of the user corresponding to the selected discussion with submit button, default is null.
    * @return void .
    */
	public function indexAction(Request &$request, FormManager $f = null, $iduserb = null) {
		$data = DefaultController::initModule($_SESSION['pseudo']);
		$data['discutitle'] = Translator::translate("Discussion");

		/*
		* Display the timeline of the current discussion
		*/
		$userrep = new Userrepository();
		$msgrep = new MessageRepository();
		if ($iduserb == null) {
			$iduserb = $msgrep->getLastDiscussion($_SESSION['iduser']); // get the ID user corresponding to the last discussion
			$fdrep = new FriendshipRepository();
			$fdshp = $fdrep->getFriendship($_SESSION['iduser'],$iduserb);
			if($fdshp->getStatus() == 0) {
				$iduserb = false;
			}
		} else {
			ProfilController::checkAllowedProfileUser($request, $iduserb);  // protection user profile
		}
		if ($iduserb != false) {
			$discussion = $msgrep->getDiscussion($_SESSION['iduser'],$iduserb);
			$data['msglist'] = array(); // list of message of the current discussion
			if (!empty($discussion)) {
				foreach ($discussion as $msg) {
					$data['msglist'][] = array("idmsg" => $msg->getIdmsg(),"url" => UrlRewriting::generateURL("Profil",$userrep->getUserPseudoById($msg->getSender())), 
						"pseudo" => $userrep->getUserPseudoById($msg->getSender()),
						"profilephoto" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($msg->getSender()),"profile_pic.png", "../default/profile_pic.png"),
						"date" => Pubdate::printDate($msg->getDate()), 
						"content" => $msg->getContent());
					$data['iduser'] = $iduserb;
                    $data['lastmsg'] = $msg->getIdmsg();
                    $msgrep->readMessages($msg->getSender(),$msg->getReceiver());
				}    
			} else {
				$data['iduser'] = $iduserb;
                $data['lastmsg'] = 0;
                $data['flashbag'] = Translator::translate("No messages!");
			}

	        /*
			* form to send a message with a textarea and a submit button
			*/
			if ($f == null) {
				$f = new FormManager("sendmsgform","sendmsgform",UrlRewriting::generateURL("SendMsg",$userrep->getUserPseudoById($iduserb)));
				$f->addField("","msg","textarea","",Translator::translate("Invalid"));
				$f->addField("Submit ","submitmsg","submit",Translator::translate("Send"),Translator::translate("Invalid"));
			}
			$data['sendmsgform'] = $f->createView(); // add the form view in the data page
		} else {
            $data['flashbag'] = Translator::translate("No messages!");
            $data['iduser'] = "''";
            $data['lastmsg'] = 0;
        }

    	/*
		* Create the list of user's friends to start a discussion
		*/
		$friendshiprep = new FriendshipRepository();
		$friends = $friendshiprep->getFriends($_SESSION['iduser']);
		if(!empty($friends)) {
			foreach ($friends as $i) {
				if ($i->getIdusera() != $_SESSION['iduser']) {
					$iduserdiscu = $i->getIdusera();
					$userfriends = $userrep->findUserById($i->getIdusera());
				} elseif ($i->getIduserb() != $_SESSION['iduser']) {
					$iduserdiscu = $i->getIduserb();
					$userfriends = $userrep->findUserById($i->getIduserb());
				}
				if($iduserdiscu == $iduserb) {
					$state = "discu-on";
				} else {
					$state = "discu-off";
				}
				$data['discussion'][] = array("state" => $state,"url" => UrlRewriting::generateURL("Discussion",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo(),
					"profilephoto" => UrlRewriting::generateSRC("userfolder", $userfriends->getPseudo(),"profile_pic.png", "../default/profile_pic.png"),);
			}
		} else {
			$data['flashbag'] = Translator::translate("No messages!");
            $data['iduser'] = "''";
            $data['lastmsg'] = 0;
		}

		$this->render("MessagesView.html.twig",$data); // create the view
	}
}

?>