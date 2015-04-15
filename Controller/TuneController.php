<?php


class TuneController extends BaseController
{
        public function deletescoreAction(Request &$request) {
            $pseudo = $request->getParameter("par")[0];
            $idtune = $request->getParameter("par")[1];
            $idscore = intval($request->getParameter("par")[2]);
            $userrep = new UserRepository();
            $tunerep = new TuneRepository();
            if ($_SESSION['iduser'] == $tunerep->getScoreUser($idscore)) {
                $file = UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($tunerep->getScoreUser($idscore)),$tunerep->getScorePdfName($idscore));
                if (file_exists($file)) {
                    unlink($file);
                }
                $tunerep->deleteScore($idscore);
            }
            $request->setParameter("par",array($pseudo, $idtune));
            $this->indexAction($request);
        }
        
        public function savescoreAction(Request &$request) {
            $pseudo = $request->getParameter("par")[0];
            $idtune = $request->getParameter("par")[1];
            $f = unserialize($_SESSION["addscoreform"]);
		    if ($f->validate($request)) {
                        unset($_SESSION["addscoreform"]);
			            $dataform = $f->getValues();
                        $salt = uniqid();
                        
                        $datescore = date("y-m-d H-i-s");
                        $tunerep = new TuneRepository();
                        $tune = new Tune($idtune,"","","", $datescore, basename($dataform['pdf']['name'],".pdf").$salt.".pdf",$_SESSION['iduser']);
                        $tunerep = new TuneRepository();
                        $tunerep->addScore($tune);
                        
                        $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pdf']['name']);
                        $img = new ImageManager($filepath);
                        $img->renameMove(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], basename($dataform['pdf']['name'],".pdf").$salt.".pdf"));
                        
                        $ctrl = new TuneController();
                        $request->setParameter("par",array($pseudo, $idtune));
                        $ctrl->indexAction($request);
                } else {
                    $this->addscoreAction($request, $f);
                }
        }
        
        public function addscoreAction(Request &$request, FormManager $f = null) {
                $data = array();
        		$pseudo = $request->getParameter("par")[0];
        		$userrep = new UserRepository();
        		$iduser = $userrep->getUserIdByPseudo($pseudo);

        		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
        		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

        		$idtune = $request->getParameter("par")[1];
        		if ($f == null) 
                {
                    $f = new FormManager("addscoreform","addscoreform",UrlRewriting::generateURL("Savescore",$pseudo."/".$idtune)); // Form to edit and publish a news
                    $f->addField("pdf score: ","pdf","file","");
                    $f->addField("Submit ","submit","submit","Import score","Error",array('id' => 'submit'));
                }
                $data['addscoreform'] = $f->createView(); // add the form view in the data page

                $data['suggestedfriends'] = FriendsController::suggestedFriends(3);

                $this->render("AddscoreView.html.twig",$data);
        }

	public function indexAction(Request &$request, FormManager $f = null) {
		$data = array();
        $pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
		$tune = $tunerep->findTuneById($idtune);
                if ($tune != false) {
                    // $pseudoowner = $userrep->getUserPseudoById($tune->getIduser());

                    $data['tune']['title'] = $tune->getTitle();
                    $data['tune']['composer'] = $tune->getComposer();
                    $data['tune']['category'] = $tune->getCategory();
                    $data['tune']['datetune'] = Pubdate::printDate($tune->getDatetune());

                    $tuneaction = array();
                    if ($_SESSION['pseudo'] == $pseudo && $tunerep->checkTuneLikedByUser($_SESSION['iduser'], $tune->getIdtune())) {
                        $tuneaction['deleteadd'] = array('name' => "Delete from your tunebook", 'class' => 'delete', 'url' => UrlRewriting::generateURL("Delete",$pseudo."/".$tune->getIdtune()));
                    } 
                    elseif (!$tunerep->checkTuneLikedByUser($_SESSION['iduser'], $tune->getIdtune())) {
                        $tuneaction['deleteadd'] = array('name' => "Add to your tunebook", 'class' => 'add', 'url' => UrlRewriting::generateURL("Add",$pseudo."/".$tune->getIdtune()));
                    } else {
                        $tuneaction['deleteadd'] = array('name' => '', 'class' => '', 'url' => "#");
                    }
                    $data['tune']["addscore"] = array("name" => "Add a new version", "url" => UrlRewriting::generateURL("Addscore",$pseudo."/".$tune->getIdtune()));
                    $data['tune']["sharetune"] = array("name" => "Share this tune", "url" =>UrlRewriting::generateURL("Share",$tune->getIdtune()));
                    $data['tune']["deleteadd"] = $tuneaction['deleteadd'];

                    $data['tune']['deletescorename'] = Translator::translate('Delete this score');
                    $data['tune']['forked'] = array();
                    $forkedusers = $tune->getForkedusers();
                    $pdfscore = $tune->getPdfscore();
                    $datescore = $tune->getDatescore();
                    $idscorelist = $tune->getIdscorelist();
                    if ($forkedusers[0] != "") {
                        for ($i=0; $i<count($forkedusers); $i++) {
                            if ($_SESSION['iduser'] == $forkedusers[$i]) {
                                $deletebutton = UrlRewriting::generateURL("Deletescore",$pseudo."/".$idtune."/".$idscorelist[$i]);
                            } else {
                                $deletebutton = "#";
                            }
                            $data['tune']['forked'][] = array("num" => ($i+1),"forkeduser" => $userrep->getUserPseudoById($forkedusers[$i]),"delete" => $deletebutton,"pdfurl" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($forkedusers[$i]), $pdfscore[$i]), "date" => Pubdate::printDate($datescore[$i]));
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