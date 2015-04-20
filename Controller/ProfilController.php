<?php
/**
 * File containing the ProfilController Class
 *
 */

/**
* ProfilController
*
* ProfilController class display the news timeline of a selected user
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class ProfilController extends BaseController
{

	/**
	* ProfilCard Module
	* Get the informations of the user pass through parameters of the method 
	* then these informations will be used ini the tempalte of this module
	*
	* @param String $pseudo pseudo of the current session user or the selected user.
	* @return array $data elements contain informations needed by the ProfilCardModule template.
	*/
	public static function profilCard($pseudo) {
		$data = array();
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

        $data['friends'] = Translator::translate("Friends");
        $data['songs'] = Translator::translate("Tunes");
		$data['pseudo'] = $pseudo;
		$data['pseudourl'] = UrlRewriting::generateURL("Profil",$pseudo);
		$data['profilephoto'] = UrlRewriting::generateSRC("userfolder", $pseudo,"profile_pic.png", "../default/profile_pic.png");
		$data['songsurl'] = UrlRewriting::generateURL("Songslist",$pseudo);
		$data['friendsurl'] = UrlRewriting::generateURL("Friends",$pseudo);
		
		if ($_SESSION['iduser'] != $iduser) {
			$user = $userrep->findUserById($iduser);
			$friendshiprep = new FriendshipRepository();
			$friendship = $friendshiprep->getFriendship($_SESSION['iduser'],$iduser);
			$status = $friendship->getStatus();
			if ($status == 0) {
				$data['statusurl'] = UrlRewriting::generateURL("askfriendship",$pseudo);
				$data['status'] = Translator::translate("Follow");
			} elseif ($status == 1) {
				$data['statusurl'] = UrlRewriting::generateURL("Friendship",$pseudo);
				$data['status'] = Translator::translate("Block");
			} else {
				$data['statusurl'] = "";
				$data['status'] = "";
			}
		}
		return $data;
	}

	/**
	* Add an invitation tuple between the two concerning users in the DB
	* if the invitation already exist => error
	* then redirect to the Home page
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function askfriendshipAction(Request &$request) {
		$userrep = new UserRepository();
		$iduser = intval($userrep->getUserIdByPseudo($request->getParameter("par")[0]));

		$invitation = new Invitation($iduser,$_SESSION['iduser'],0,date("y-m-d H-i-s"));
		$invitationrep = new InvitationRepository();
		if ($invitationrep->addInvitation($invitation) == 2) {
			$flashbag = Translator::translate("An invitation has already been sent!");
		} else {
			$flashbag = Translator::translate("The invitation has been sent!");
		}
		$ctrl = new HomeController();
		$ctrl->indexAction($request, $f = null, $flashbag);
		exit(1);
	}

	/**
	* Method call by clicking on "Follow" or "Block"
	* so it will add or remove a user from your friends
	* then display the profile page
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function friendshipstatusAction(Request &$request) {
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

		$this->indexAction($request);
	}

	/**
	* check if the selected profile user is accessible (if the current is friends with the selected user)
	* else redirect the current user to the BlockedProfile page
	*
	* @param Request &$request Request object.
	* @param int $iduser id of the selected user.
	* @return void .
	*/
	public static function checkAllowedProfileUser(Request &$request, $iduser) {
		$fdrep = new FriendshipRepository();
		$fdstatus = $fdrep->getFriendship($_SESSION['iduser'], $iduser)->getStatus();
		if ($_SESSION['iduser'] != $iduser &&  $fdstatus != 1){
		   $ctrler = new BlockedprofilController();
		   $ctrler->indexAction($request);
		   exit(1);
		}
	}
	
	/**
    * Create the default profile page view
    *
    * Display a timeline of the selected user with the Profile Card module and the Suggested friends module of the session user
    *
    * @param Request &$request Request object.
    * @param FormManager $f optional. contain a form object, default is null.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function indexAction(Request &$request, $flashbag = null) {
		/*
		* init the pseudo and iduser of the selected user
		*/
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		self::checkAllowedProfileUser($request, $iduser); // protection user profile

		$data = DefaultController::initModule($pseudo);
                
		/*
		* News timeline of the selected user
		*/
		$newsrep = new NewsRepository();
		$newslist = $newsrep->findAllNewsUser($iduser);
		if (!empty($newslist[0])) {
            foreach ($newslist as $news) {
                $data['newslist'][] = array("url" => UrlRewriting::generateURL("Profil",$news->getUserpseudo()), "user" => $news->getUserpseudo(),
                                "profilephoto" => UrlRewriting::generateSRC("userfolder", $news->getUserpseudo(),"profile_pic.png", "../default/profile_pic.png"),
                                "pubdate" => Pubdate::printDate($news->getPubdate()),
                                "content" => $news->getContent());
            }
        } else {
            $data['flashbag']= "No news";
        }

		$this->render("ProfilView.html.twig",$data);
	}
}

?>