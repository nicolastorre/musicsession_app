<?php


class TuneController extends BaseController
{

	public function indexAction(Request &$request, FormManager $f = null) {
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

		$idtune = $request->getParameter("par")[0];
		$tunerep = new TuneRepository();
		$tune = $tunerep->findTuneById($idtune);
        if ($tune != false) {
            $pseudoowner = $userrep->getUserPseudoById($tune->getIduser());

            $data['tune']['title'] = $tune->getTitle();
            $data['tune']['composer'] = $tune->getComposer();
            $data['tune']['category'] = $tune->getCategory();
            $data['tune']['datetune'] = $tune->getDatetune();
            $data['tune']['url'] = UrlRewriting::generateSRC("userfolder", $pseudoowner, $tune->getPdf());
        } else {
            throw new Exception('Not existing tune!');
        }

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);
		
		$this->render("TuneView.html.twig",$data);
	}
}

?>