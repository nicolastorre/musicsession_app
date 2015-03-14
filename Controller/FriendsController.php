<?php
/**
 * File containing the FriendsController Class
 *
 */

/**
 * FriendsController
 *
 * FriendsController class manage the friends page features.
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class FriendsController extends BaseController
{

	/**
     * Suggested Friends module
     *
     * Display some future potential friends in the up-right dashboard of the page
     * This module is used in every page of the website.
     *
     * @param int $nbsuggestion number of suggested friends to display.
     * @return array $suggestedfriends array containing array referencing suggested friends
     * with  iduser, pseudo and url of each suggested friends.
     */
	public static function suggestedFriends($nbsuggestion) {
		$suggestedFriends = array();
		$userrep = new UserRepository();
		$alliduser = $userrep->allIdUser();
		$nbuser = count($alliduser);
		$friendshiprep = new FriendshipRepository();
		$iduser = $userrep->getUserIdByPseudo($_SESSION['pseudo']);
		$friends = $friendshiprep->getFriends($iduser);
		$idfriends = array();
		foreach ($friends as $i) {
			if ($i->getIdusera() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIdusera());
			} elseif ($i->getIduserb() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIduserb());
			}
			$idfriends[] = $userfriends->getIduser();		
		}
		if ($nbuser>count($idfriends)+1) {
			for ($i=0;$i<$nbsuggestion;$i++) {
				do {
					$iduserrand = $alliduser[rand(0,$nbuser-1)];
				} while (($iduserrand == $iduser ) || in_array($iduserrand,$idfriends));
				$userrand = $userrep->findUserById($iduserrand);
				$userrand_pseudo = $userrand->getPseudo();
				$suggestedFriends[] = array('iduser' => $iduserrand, 'pseudo' => $userrand_pseudo, 'url' => UrlRewriting::generateURL("Profil",$userrand_pseudo));
			}
		}
		return $suggestedFriends;
	}
	
	/**
     * Create the default friends page view
     *
     * Display a tlist of user's friends, the Profile Card module and the Suggested friends module
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request) {
		/*
		* Initialization of the page variables
		*/
		$data = array();
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);		

		$data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module

		/*
		* Create the list of friends for the session user
		*/
		$friendshiprep = new FriendshipRepository();
		$friends = $friendshiprep->getFriends($iduser);
		foreach ($friends as $i) {
			if ($i->getIdusera() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIdusera());
			} elseif ($i->getIduserb() != $iduser) {
				$userfriends = $userrep->findUserById($i->getIduserb());
			}
			$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo());
		}

		$data['suggestedfriends'] = self::suggestedFriends(3); // init the Suggested Friends module

		$this->render("FriendsView.html.twig",$data); // create the view
	}

}

?>