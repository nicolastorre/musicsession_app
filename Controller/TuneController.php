<?php


class TuneController extends BaseController
{
        public function deletescoreAction(Request &$request) {
            $idscore = intval($request->getParameter("par")[1]);
            $userrep = new UserRepository();
            $tunerep = new TuneRepository();
            if ($_SESSION['iduser'] == $tunerep->getScoreUser($idscore)) {
                $file = UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($tunerep->getScoreUser($idscore)),$tunerep->getScorePdfName($idscore));
                if (file_exists($file)) {
                    unlink($file);
                }
                $tunerep->deleteScore($idscore);
            }
            $this->indexAction($request);
        }
        
        public function savescoreAction(Request &$request) {
            $idtune = $request->getParameter("par")[0];
            $f = unserialize($_SESSION["addscoreform"]);
		if ($f->validate($request)) {
                        unset($_SESSION["addscoreform"]);
			$dataform = $f->getValues();
                        $salt = uniqid();
                        
                        $datescore = date("y-m-d H-i-s");
                        $tunerep = new TuneRepository();
                        $tune = new Tune($idtune,$_SESSION['iduser'],"","","", $datescore, basename($dataform['pdf']['name'],".pdf").$salt.".pdf");
                        $tunerep = new TuneRepository();
                        $tunerep->addScore($tune);
                        
                        $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pdf']['name']);
                        $img = new ImageManager($filepath);
                        $img->renameMove(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], basename($dataform['pdf']['name'],".pdf").$salt.".pdf"));
                        
                        $ctrl = new TuneController();
                        $ctrl->indexAction($request);
                } else {
                    $this->addscoreAction($request, $f);
                }
        }
        
        public function addscoreAction(Request &$request, FormManager $f = null) {
                $data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

		$idtune = $request->getParameter("par")[0];
		if ($f == null) 
                {
                    $f = new FormManager("addscoreform","addscoreform",UrlRewriting::generateURL("Savescore",$idtune)); // Form to edit and publish a news
                    $f->addField("pdf score: ","pdf","file","");
                    $f->addField("Submit ","submit","submit","Import score","Error",array('id' => 'submit'));
                }
                $data['addscoreform'] = $f->createView(); // add the form view in the data page

                $data['suggestedfriends'] = FriendsController::suggestedFriends(3);

                $this->render("AddscoreView.html.twig",$data);
        }

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
                    $data['tune']['datetune'] = Pubdate::printDate($tune->getDatetune());
                    
                    $data['tune']['forked'] = array();
                    $forkedusers = $tune->getForkedusers();
                    $pdfscore = $tune->getPdfscore();
                    $datescore = $tune->getDatescore();
                    $idscorelist = $tune->getIdscorelist();
                    if ($forkedusers[0] != "") {
                        for ($i=0; $i<count($forkedusers); $i++) {
                            if ($_SESSION['iduser'] == $forkedusers[$i]) {
                                $deletebutton = UrlRewriting::generateURL("Deletescore",$idtune."/".$idscorelist[$i]);
                            } else {
                                $deletebutton = "#";
                            }
                            $data['tune']['forked'][] = array("delete" => $deletebutton,"pdfurl" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($forkedusers[$i]), $pdfscore[$i]), "date" => $datescore[$i]);
                        }
                    } else {
                        $data['tune']['forked'][] = array("delete" => "", "pdfurl" => "", "date" => "");
                    }
                    
                } else {
                    throw new Exception('Not existing tune!');
                }

                $data['suggestedfriends'] = FriendsController::suggestedFriends(3);

                $this->render("TuneView.html.twig",$data);
        }
}

?>