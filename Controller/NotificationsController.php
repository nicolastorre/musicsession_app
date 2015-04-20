<?php
/**
 * File containing the NotificationsController Class
 *
 */

/**
* NotificationsController
*
* NotificationsController class display the friends following events and the asking of following
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class NotificationsController extends BaseController
{
	/**
    * Send number of non-read invitation via AJAX
    *
    * @return void .
    */
	public function readerAction() {
		$iduser = $_SESSION['iduser'];
		$invitationrep = new InvitationRepository();
		$readd = $invitationrep->getNonReadInvitation($iduser);
		header('Content-type: application/json');
        echo json_encode($readd);
	}

	/**
    * Send an invitation e-mail to a friend of the current user
    * redirect to the notifications page at the end
    *
    * @param Request &$request Request object.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function invitefriendAction(Request &$request) {
		if(!isset($_SESSION["invitefriendform"])) throw new Exception("\$_SESSION['invitefriendform'] does'nt exist!");

		$f = unserialize($_SESSION["invitefriendform"]); // get back the form object with the submitted news
                
		if ($f->validate($request)) { // check for valide data.
            unset($_SESSION["invitefriendform"]);
                        
			$dataform = $f->getValues(); // extract the data from the form

			//send e-mail 
            $msg = Translator::translate("Hello")."<p>".Translator::translate("Discover Music session network here: ")."<a href='".ConfigReader::get("webroot", "/")."'>Music session</a><p><p>".Translator::translate("Message of ").$_SESSION['pseudo']."</p><p>".$dataform['invitingmsg']."</p><br><h2>Music session</h2>";
            $mail = new Mailer();
            $mail->sendmail(Translator::translate("Invitation Music session"), "musicsession@gmail.com", "Music Session", $dataform['emailfriend'], "Friend", $msg);

			$this->indexAction($request, $f = null, Translator::translate("E-mail sent!"));
		} else {
			$this->indexAction($request, $f);
		}
	}

	/**
    * Accept an invitation from a user and become friend of this user
    * redirect to the notifications page at the end
    *
    * @param Request &$request Request object.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function confirminvitationAction(Request &$request) {
		$userrep = new UserRepository();
		$iduser = intval($userrep->getUserIdByPseudo($request->getParameter("par")[0]));

		
		$friendshiprep = new FriendshipRepository();
		$friendship = $friendshiprep->getFriendship($_SESSION['iduser'],$iduser);
		$status = $friendship->getStatus();
		if($status == 1) {
			$friendship->setStatus(0);
		} elseif($status == 0) {
			$friendship->setStatus(1);
		}
		$friendshiprep->update($friendship); // update the friendhip between the session user and the selected user

		$invitationrep = new invitationRepository();
		$invitationrep->deleteinvitation($_SESSION['iduser'],$iduser);
		$this->indexAction($request);
	}
	
	/**
    * Create the default notifications page view
    *
    * Display a timeline of the friends following events for the session user, the Profile Card module and the Suggested friends module
    *
    * @param Request &$request Request object.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function indexAction(Request &$request, FormManager $f = null, $flashbag = null) {
		$data = DefaultController::initModule($_SESSION['pseudo']);

		$data['flashbag'] = $flashbag;
		$data['invitationlink'] = Translator::translate("Accept the invitation");

		$userrep = new UserRepository();
		$friendshiprep = new FriendshipRepository();
		$friends = $friendshiprep->getFriends($_SESSION['iduser']);
		foreach ($friends as $i) {
			if ($i->getIdusera() != $_SESSION['iduser']) {
				$userfriends = $userrep->findUserById($i->getIdusera());
			} elseif ($i->getIduserb() != $_SESSION['iduser']) {
				$userfriends = $userrep->findUserById($i->getIduserb());
			}
			$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo(),
				"rawdate" => $i->getDate(),
				"datefd" => Pubdate::printDate($i->getDate()),
				"profilephoto" => UrlRewriting::generateSRC("userfolder", $userfriends->getPseudo(),"profile_pic.png", "../default/profile_pic.png"),
				"desc" => Translator::translate(" is friend with you!"));
		}

		$invitationrep = new invitationRepository();
		$invitationlist = $invitationrep->getAllInvitation($_SESSION['iduser']);
		if(!is_null($invitationlist)) {
			foreach($invitationlist as $invitation) {
				$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userrep->getUserPseudoById($invitation->getIduserb())), "pseudo" => $userrep->getUserPseudoById($invitation->getIduserb()),
					"rawdate" => $invitation->getDate(),
					"datefd" => Pubdate::printDate($invitation->getDate()),
					"profilephoto" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($invitation->getIduserb()),"profile_pic.png", "../default/profile_pic.png"),
					"desc" => Translator::translate(" send you an invitation!"),
					"invitationurl" => UrlRewriting::generateURL("confirminvitation",$userrep->getUserPseudoById($invitation->getIduserb())));
				$invitationrep->readInvitation($invitation->getIdusera(),$invitation->getIduserb());
			}
		}

		if(isset($data['friends'])) {
			usort($data['friends'], function($a,$b){ 
					$datea = new DateTime($a['rawdate']);
					$tmsta = $datea->getTimestamp();
			        $dateb = new DateTime($b['rawdate']);
			        $tmstb = $dateb->getTimestamp();
					return $tmstb-$tmsta;
				} 
			);
		} else {
			$data['flashbag'] = Translator::translate("No notifications");
		}

		if ($f == null) {
			$f = new FormManager("invitefriendform","invitefriendform",UrlRewriting::generateURL("invitefriend","")); // Form to edit and publish a news
			$f->addField(Translator::translate("E-mail friend: "),"emailfriend","email","",Translator::translate("Invalid"));
			$f->addField(Translator::translate("Invitation message: "),"invitingmsg","textarea","",Translator::translate("Invalid"));
			$f->addField("Submit ","submitinvitation","submit",Translator::translate("Send"));	
		}
		$data['invitefriendform'] = $f->createView(); // add the form view in the data page
		$data['invitefriendtitle'] = Translator::translate("Invite your friends!");

		$this->render("NotificationsView.html.twig",$data);
	}
}

?>