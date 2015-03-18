<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class NotificationsController extends BaseController
{
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request) {
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);		

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);

		$friendshiprep = new FriendshipRepository();
		$friends = $friendshiprep->getFriends($iduser);
		foreach ($friends as $i) {
			if ($i->getIdusera() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIdusera());
			} elseif ($i->getIduserb() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIduserb());
			}
			$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo(),
				"datefd" => $i->getDate(),
				"profilephoto" => UrlRewriting::generateSrcUser($userfriends->getPseudo(),"profile_pic.png"),);
		}

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);

		$this->render("NotificationsView.html.twig",$data);
	}

}

?>