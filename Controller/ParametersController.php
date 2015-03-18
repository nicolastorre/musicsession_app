<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class ParametersController extends BaseController
{

	public function updateparametersAction(Request &$request) {
		$f = unserialize($_SESSION["parametersform"]);
		if ($f->validate($request)) {
			$dataform = $f->getValues();
			$filepath = "tmp/".$dataform['pic']['name'];
			$img = new ImageManager($filepath);
			$img->renameMove($_SESSION['pseudo'].".png");

			// test if pseudo is unique in the DB

			$userrep = new UserRepository();
			$user = $userrep->findUserById($_SESSION['iduser']);
			$user->setpseudo($dataform['pseudo']);
			$_SESSION['pseudo'] = $dataform['pseudo'];
			$user->setEmail($dataform['email']);
			$user->setLang($dataform['lang'][0]);
			
			$userrep->updateUser($user);
			$this->indexAction($request);

		} else {
			$this->indexAction($request, $f);
		}
	}
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request, FormManager $f = null) {
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

		if ($f == null) {
			$user = $userrep->findUserById($iduser);
			$f = new FormManager("parametersform","parametersform",UrlRewriting::generateURL("updateparameters",""));
			$f->addField("Pseudo: ","pseudo","text",$user->getPseudo());
			$f->addField("E-mail: ","email","email",$user->getEmail());
			$languser = $user->getLang();
			$langvalues = array(array('v' => "fr",'s' => false),array('v' => "en",'s' => false));
			for ($i=0; $i<count($langvalues);$i++) {
				if ($langvalues[$i]['v'] == $languser) {
					$langvalues[$i]['s'] = true;
				}
			}

			$f->addField("Language: ","lang","select",$langvalues);
			$f->addField("Profile photo: ","pic","image","");
			$f->addField("Submit ","submit","submit","Update");	
		}
		$data['parametersform'] = $f->createView();

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);

		$this->render("ParametersView.html.twig",$data);
	}

}

?>