<?php
/**
 * File containing the FriendsController Class
 *
 */

/**
 * FriendsController
 *
 * FriendsController class manage the suggestedfriends module and display the friends page
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
        $friendshiprep = new FriendshipRepository();
        $suggestedfdlist = $friendshiprep->suggestFriends($_SESSION['iduser']);
        $suggestedFriends['title'] = Translator::translate("Suggested friends");
        for ($i=0; $i<$nbsuggestion; $i++) {
            $k = rand(0,count($suggestedfdlist)-1);
            if (isset($suggestedfdlist[$k])) {
            $userrand = $userrep->findUserById($suggestedfdlist[$k]['id_user']);
            $userrand_pseudo = $userrand->getPseudo();
            $suggestedFriends['user'][] = array('iduser' => $suggestedfdlist[$k]['id_user'], 'pseudo' => $userrand_pseudo, 'url' => UrlRewriting::generateURL("Profil",$userrand_pseudo),
    			"profilephoto" => UrlRewriting::generateSRC("userfolder", $userrand_pseudo,"profile_pic.png", "../default/profile_pic.png"));
            unset($suggestedfdlist[$k]);
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
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

        ProfilController::checkAllowedProfileUser($request, $iduser);  // protection user profile

        $data = DefaultController::initModule($pseudo);	

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
			$data['friends'][] = array("url" => UrlRewriting::generateURL("Profil",$userfriends->getPseudo()), "pseudo" => $userfriends->getPseudo(),
				"profilephoto" => UrlRewriting::generateSRC("userfolder", $userfriends->getPseudo(),"profile_pic.png", "../default/profile_pic.png"),);
		}

		$this->render("FriendsView.html.twig",$data); // create the view
	}
}

?>