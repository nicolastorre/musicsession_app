<?php
/**
 * File containing the MessagesController Class
 *
 */

/**
 * MessagesController
 *
 * MessageseController class manage the Messages page features
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class MessagesController extends BaseController
{

	/**
     * Get, treat and save a submitted news 
     * then if no errors => reload the default home  page 
     * else => reload the home page with the previous form
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function getdiscussionAction(Request &$request) {
		$userrep = new UserRepository();
		$iduserb = $userrep->getUserIdByPseudo($request->getParameter("par")[0]);
		$this->indexAction($request, $f = null, $iduserb);

	}

	/**
     * Get, treat and save a submitted news 
     * then if no errors => reload the default home  page 
     * else => reload the home page with the previous form
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function sendmsgAction(Request &$request) {
		$f = unserialize($_SESSION["sendmsgform"]);
		$userrep = new UserRepository();
		$iduserb = $userrep->getUserIdByPseudo($request->getParameter("par")[0]);
		if ($f->validate($request)) {
			$dataform = $f->getValues();
			$date_msg = date("y-m-d H-i-s");
			$msg = new Message($_SESSION['iduser'],$iduserb,$date_msg,$dataform['msg']);
			$msgrep = new MessageRepository();
			$msgrep->sendMsg($msg);
			$this->indexAction($request, $f = null, $iduserb);

		} else {
			$this->indexAction($request, $f);
		}
	}
	
	// Principal action of the HomeController 
	/**
     * Create the default messages page view
     *
     * Display a timeline of the current discussion, the list of user's friends to start a discussion, 
     * the Profile Card module and the Suggested friends module
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null, $iduserb = null) {
		/*
		* Initialization of the page variables
		*/
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module

		/*
		* Create the the list of user's friends to start a discussion
		*/
		$userrep = new Userrepository();
		$friendshiprep = new FriendshipRepository();
		$friends = $friendshiprep->getFriends($iduser);
		foreach ($friends as $i) {
			if ($i->getIdusera() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIdusera());
			} elseif ($i->getIduserb() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIduserb());
			}
			$data['discussion'][] = array("url" => UrlRewriting::generateURL("Discussion",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo());
		}

		/*
		* Display the timeline of the current discussion
		*/
		$msgrep = new MessageRepository();
		
		if ($iduserb == null) {
			$iduserb = $msgrep->getLastDiscussion($iduser); // get the ID user corresponding to the last discussion
		}

		$discussion = $msgrep->getDiscussion($iduser,$iduserb);
		$data['msglist'] = array(); // list of message of the current discussion
		if (!empty($discussion)) {
			foreach ($discussion as $msg) {
				$data['msglist'][] = array("url" => UrlRewriting::generateURL("Profil",$userrep->getUserPseudoById($msg->getSender())), "pseudo" => $userrep->getUserPseudoById($msg->getSender()), "date" => $msg->getDate(), "content" => $msg->getContent());
			}
		}

		// form to send a message
		if ($f == null) {
			$f = new FormManager("sendmsgform","sendmsgform",UrlRewriting::generateURL("SendMsg",$userrep->getUserPseudoById($iduserb)));
			$f->addField("","msg","textarea","");
			$f->addField("Submit ","submit","submit","Send");
		}
		$data['sendmsgform'] = $f->createView(); // add the form view in the data page

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3); // init the Suggested Friends module

		$this->render("MessagesView.html.twig",$data); // create the view
	}

}

?>