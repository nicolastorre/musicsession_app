<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class ParametersController extends BaseController
{
        public function updatepwdAction(Request &$request) {
            $h = unserialize($_SESSION["updatepwd"]);
            if ($h->validate($request)) {
                $dataform = $h->getValues();
                if ($dataform['pwd'] == $dataform['confirmpwd']) {
                
                $userrep = new UserRepository();
                $user = $userrep->findUserById($_SESSION['iduser']);
                $user->setPwdhashed($dataform['pwd']);

                $userrep->updateUser($user);
                
                $this->indexAction($request);
                } else {
                    $this->indexAction($request, $f = null, $g = null, $h);
                }
            }  else {
		$this->indexAction($request, $f = null, $g = null, $h);
            }
        }
        
        public function updatephotoAction(Request &$request) {
            $g = unserialize($_SESSION["updatephoto"]);
            if ($g->validate($request)) {
                $dataform = $g->getValues();

                $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pic']['name']);
                $img = new ImageManager($filepath);
                $img->convertInProfilePic(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'],"profile_pic.png"));
                
                // header('Content-Type: text/html; charset=utf-8');
                $this->indexAction($request);
            } else {
				$this->indexAction($request, $f = null, $g);
            }
        }

	public function updateparametersAction(Request &$request) {
		$f = unserialize($_SESSION["parametersform"]);
		if ($f->validate($request)) {
			$dataform = $f->getValues();
			
			// test if pseudo is unique in the DB
                        if (preg_match('/[^-_a-z0-9.]/iu', $dataform['pseudo']))
                        {
                            $this->indexAction($request, $f = null, $g = null, $h = null, "Special char not allowed in pseudo!");
                        } else {
                            $userrep = new UserRepository();
                            if ($dataform['pseudo']==$_SESSION['pseudo'] || !$userrep->existUserPseudo($dataform['pseudo'])) {
                                $user = $userrep->findUserById($_SESSION['iduser']);
                                $user->setpseudo($dataform['pseudo']);
                                $_SESSION['pseudo'] = $dataform['pseudo'];
                                $user->setEmail($dataform['email']);
                                $user->setLang($dataform['lang'][0]);
                                $_SESSION['lang'] = $dataform['lang'][0];
                                $userrep->updateUser($user);
                                $this->indexAction($request);
                            } else {
                                $this->indexAction($request, $f = null, $g = null, $h = null, "Pseudo already exist!");
                            }
                        }

		} else {
			$this->indexAction($request, $f);
		}
	}
	
	// Principal action of the HomeController 
	public function indexAction(Request &$request, FormManager $f = null, FormManager $g = null, FormManager $h = null, $flashbag = null) {
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();
                
                $data['flashbag'] = $flashbag;

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
			
			$f->addField("Submit ","submit","submit","Update");	
		}
		$data['parametersform'] = $f->createView();

		if ($g == null) {
			$g = new FormManager("updatephoto","updatephoto",UrlRewriting::generateURL("updatephoto",""));
                        $g->addField("Profile photo: ","pic","file","");
			$g->addField("Submitphoto","submit","submit","Update");	
		}
		$data['updatephoto'] = $g->createView();
                
                if ($h == null) {
			$h = new FormManager("updatepwd","updatepwd",UrlRewriting::generateURL("updatepassword",""));
                        $h->addField("Password: ","pwd","text","");
                        $h->addField("Confirm your password: ","confirmpwd","text","");
			$h->addField("Submitphoto","submit","submit","Update");	
		}
		$data['updatepwd'] = $h->createView();
                
		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);

		$this->render("ParametersView.html.twig",$data);
	}
}

?>