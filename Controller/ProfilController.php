<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class ProfilController extends BaseController
{

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
				$data['statusurl'] = UrlRewriting::generateURL("Friendship",$pseudo);
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
		
		$friendshiprep->update($friendship);

		$this->indexAction($request);
	}
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request) {
		$data = array();
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);
                
//                Controle if user > ami >>> autorisation de l'accès au profile sinon bloque
		$fdrep = new FriendshipRepository();
		$fdstatus = $fdrep->getFriendship($_SESSION['iduser'], $iduser)->getStatus();
		if ($_SESSION['iduser'] != $iduser &&  $fdstatus != 1){
		   $ctrler = new BlockedprofilController();
		   $ctrler->indexAction($request);
		} else {

			$data['profilcard'] = self::ProfilCard($pseudo);
			$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

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

			$data['suggestedfriends'] = FriendsController::suggestedFriends(3);

			$this->render("ProfilView.html.twig",$data);
		}
	}

}

?>