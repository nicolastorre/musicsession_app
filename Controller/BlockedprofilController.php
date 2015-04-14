<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class BlockedprofilController extends BaseController
{
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request) {
		$data = array();
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);
//                Controle if user > ami >>> autorisation de l'accès au profile sinon bloque

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();
                
                $data['flashbag'] = "You're not allowed to view this profile";

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);

		$this->render("ProfilView.html.twig",$data);
	}

}

?>