<?php
/**
 * File containing the BackofficeController Class
 *
 */

/**
 * BackofficeController
 *
 * BackofficeController class manage the Backoffice features
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class BackofficeController extends BaseController
{
    /**
    * Get, treat the form with an input text containing a username 
    * if the form is valid => delete account and all informations about the corresponding user
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function deleteuserAction(Request &$request) {
        if (!isset($_SESSION["deleteuserform"])) throw new Exception("\$_SESSION['deleteuserform'] doesn't exist!");

        $f = unserialize($_SESSION["deleteuserform"]); // get back the form object with the submitted news
        if ($f->validate($request)) { // check for valide data.
                unset($_SESSION["deleteuserform"]);
                $dataform = $f->getValues(); // extract the data from the form
                
                $userrep = new UserRepository();
                $iduser = $userrep->getUserIdByPseudo($dataform['userpseudo']);

                // delete the user directory
                $dir = UrlRewriting::generateSRC("userfolder", $dataform['userpseudo'],"");
                chmod($dir,0755);
                $files = scandir($dir);                       
                foreach ($files as $i) {
                    if ($i != "." && $i != "..") {
                        chmod($dir.$i,0755);
                        unlink($dir.$i);    
                    }
                }
                rmdir($dir);

                // delete all the user's tune
                $tunerep = new TuneRepository();
                $tunerep->deleteAllTuneUser($iduser);

                // delete all the user's news
                $newsrep = new NewsRepository();
                $newsrep->deleteAllNewsUser($iduser);

                // delete all the user's friendship
                $fdrep = new FriendshipRepository();
                $fdrep->deleteAllFdUser($iduser);

                // Finish the action by deleting the user
                if ($userrep->deleteuser($iduser)) {
                    $this->indexAction($request,$f = null, Translator::translate("Deleting succeded!"));
                } else {
                    $this->indexAction($request, $f, Translator::translate("Error"));
                }
        } else {
            $this->indexAction($request, $f, Translator::translate("Error"));
        }
    }

	/**
     * Create the default backoffice page view
     *
     * Display a form to delete user's account and a timeline with all abusive behavior reports
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form to delete user's account, default is null.
     * @param String $flashbag return error message.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null, $flashbag = null) {
        $data = DefaultController::initModule($_SESSION['pseudo']);
        
        $data['flashbag'] = $flashbag;
                
        if ($f == null) {
			$f = new FormManager("deleteuserform","deleteuserform",UrlRewriting::generateURL("Deleteuser",""));
			$f->addField(Translator::translate("pseudo: "),"userpseudo","text","",Translator::translate("Invalid"));
			$f->addField("Submit ","submituserpseudo","submit",Translator::translate("Delete"));	
		}
		$data['deleteuserform'] = $f->createView(); // add the form view in the data page

        $data['deleteusertitle'] = Translator::translate("Remove user account and all user informations");
        $data['reporttitle'] = Translator::translate("Reporting abusive behavior");
        $reportrep = new ReportRepository();
        $reportlist = $reportrep->findAllReport();

        foreach ($reportlist as $report) {
            $data['reportlist'][] = array("url" => UrlRewriting::generateURL("Profil",$report->getUserpseudo()), "user" => $report->getUserpseudo(),
                    "profilephoto" => UrlRewriting::generateSRC("userfolder", $report->getUserpseudo(),"profile_pic.png", "../default/profile_pic.png"),
                    "pubdate" => Pubdate::printDate($report->getPubdate()),
                    "content" => $report->getContent());
        }


		$this->render("BackofficeView.html.twig",$data); // create the view
	}
}

?>