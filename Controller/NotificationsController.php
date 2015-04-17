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
	public function indexAction(Request &$request, $flashbag = null) {
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
				"datefd" => Pubdate::printDate($i->getDate()),
				"profilephoto" => UrlRewriting::generateSRC("userfolder", $userfriends->getPseudo(),"profile_pic.png", "../default/profile_pic.png"),
				"desc" => Translator::translate(" is friend with you!"));
		}

		$invitationrep = new invitationRepository();
		$invitationlist = $invitationrep->getAllInvitation($_SESSION['iduser']);
		if(!is_null($invitationlist)) {
			foreach($invitationlist as $invitation) {
				$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userrep->getUserPseudoById($invitation->getIduserb())), "pseudo" => $userrep->getUserPseudoById($invitation->getIduserb()),
					"datefd" => Pubdate::printDate($invitation->getDate()),
					"profilephoto" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($invitation->getIduserb()),"profile_pic.png", "../default/profile_pic.png"),
					"desc" => Translator::translate(" send you an invitation!"),
					"invitationurl" => UrlRewriting::generateURL("confirminvitation",$userrep->getUserPseudoById($invitation->getIduserb())));
			}
		}
	
		$this->render("NotificationsView.html.twig",$data);
	}
}

?>