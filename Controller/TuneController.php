<?php
/**
* File containing the TuneController Class
*
*/

/**
* TuneController
*
* TuneController class display a tune of a user with its different versions and different available actions
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class TuneController extends BaseController
{
    /**
    * Download a score, only allowed for the user which is owning the concerning version
    * download the pdf score file
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function downloadscoreAction(Request &$request) {
        $pseudo = $request->getParameter("par")[0];
        $idtune = $request->getParameter("par")[1];
        $idscore = intval($request->getParameter("par")[2]);
        $userrep = new UserRepository();
        $tunerep = new TuneRepository();
        $file = UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($tunerep->getScoreUser($idscore)),$tunerep->getScorePdfName($idscore));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    /**
    * Delete a score, only allowed for the user which is owning the concerning version
    * delete the pdf file and the version tuple in the DB 
    *
    * @param Request &$request Request object.
    * @return void .
    */
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

    /**
    * if the form is valid => Add a version to a tune
    * Save the new version tuple in the DB
    * Download the pdf file in the user directory
    * else => redirect to the previous form
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function savescoreAction(Request &$request) {
        $pseudo = $request->getParameter("par")[0];
        $idtune = $request->getParameter("par")[1];

        if(!isset($_SESSION["addscoreform"])) throw new Exception("\$_SESSION['addscoreform']doesn't exist!");

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

    /**
    * Display a form to add a version to a tune
    * Save the new version tuple in the DB
    * Download the pdf file in the user directory
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function addscoreAction(Request &$request, FormManager $f = null) {
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

        ProfilController::checkAllowedProfileUser($request, $iduser); // protection user profile

        $data = DefaultController::initModule($pseudo);

		$idtune = $request->getParameter("par")[1];
		if ($f == null) 
        {
            $f = new FormManager("addscoreform","addscoreform",UrlRewriting::generateURL("Savescore",$pseudo."/".$idtune)); // Form to edit and publish a news
            $f->addField(Translator::translate("Music score (pdf): "),"pdf","file","",Translator::translate("Invalid"),$attr = array(),$raw = false,"pdf");
            $f->addField("Submit ","submit","submit","Import score","Error",array('id' => 'submit'));
        }
        $data['addscoreform'] = $f->createView(); // add the form view in the data page

        $this->render("AddscoreView.html.twig",$data);
    }

    /**
    * Create the default tune page view
    *
    * Display a tune with all of this version in iframe and action button to add/delete of the user tunebook,
    * add a new version, share the tune on the user news timeline and delete each version of the tune
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function indexAction(Request &$request) {
        $pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

        ProfilController::checkAllowedProfileUser($request, $iduser); // protection user profile

        $data = DefaultController::initModule($pseudo);

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
                $tuneaction['deleteadd'] = array('name' => Translator::translate("Delete from your tunebook"), 'class' => 'delete', 'url' => UrlRewriting::generateURL("Delete",$pseudo."/".$tune->getIdtune()));
            } 
            elseif (!$tunerep->checkTuneLikedByUser($_SESSION['iduser'], $tune->getIdtune())) {
                $tuneaction['deleteadd'] = array('name' => Translator::translate("Add to your tunebook"), 'class' => 'add', 'url' => UrlRewriting::generateURL("Add",$pseudo."/".$tune->getIdtune()));
            } else {
                $tuneaction['deleteadd'] = array('name' => '', 'class' => '', 'url' => "#");
            }
            $data['tune']["addscore"] = array("name" => Translator::translate("Add a new version"), "url" => UrlRewriting::generateURL("Addscore",$pseudo."/".$tune->getIdtune()));
            $data['tune']["sharetune"] = array("name" => Translator::translate("Share this tune"), "url" =>UrlRewriting::generateURL("Share",$tune->getIdtune()));
            $data['tune']["deleteadd"] = $tuneaction['deleteadd'];

            $data['tune']['deletescorename'] = Translator::translate('Delete this score');
            $data['tune']['downloadscorename'] = Translator::translate('Download this score');
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
                    $downloadbutton = UrlRewriting::generateURL("Downloadscore",$pseudo."/".$idtune."/".$idscorelist[$i]);
                    $data['tune']['forked'][] = array("num" => ($i+1),"forkeduser" => $userrep->getUserPseudoById($forkedusers[$i]),'download' => $downloadbutton,"delete" => $deletebutton,"pdfurl" => UrlRewriting::generateSRC("userfolder", $userrep->getUserPseudoById($forkedusers[$i]), $pdfscore[$i]), "date" => Pubdate::printDate($datescore[$i]));
                }
            } else {
                $data['tune']['forked'][] = array("delete" => "", "pdfurl" => "", "date" => "");
            }
            
        } else {
            throw new Exception('Not existing tune!');
        }

        $this->render("TuneView.html.twig",$data);
    }
}

?>