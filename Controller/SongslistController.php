<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class SongslistController extends BaseController
{
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request, FormManager $f = null) {
		$data = array();
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);

		$tunerep = new TuneRepository();
		$tunelist = $tunerep->FindUserLikedtune($iduser);
		$i = 1;
		foreach ($tunelist as $tune) {
			if ($i%2 == 0) {
				$class = "item-even";
			} else {
				$class = "item-odd";
			}
			$data['tunelist'][] = array("class" => $class,
				"idtune" => $tune->getIdtune(), 
				"iduser" => $tune->getIduser(), 
				"title" => $tune->getTitle(), 
				"composer" => $tune->getComposer(), 
				"category" => $tune->getCategory(), 
				"datetune" => $tune->getDatetune(), 
				"pdf" => $tune->getPdf());
			$i++;
		}

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);
		
		$this->render("SongslistView.html.twig",$data);
	}

}

?>