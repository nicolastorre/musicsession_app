<?php
/**
 * File containing the HomeController Class
 *
 */

/**
 * HomeController
 *
 * HomeController class manage the Home page features
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class BackofficeController extends BaseController
{
        public function deleteuserAction(Request &$request) {
                $f = unserialize($_SESSION["deleteuserform"]); // get back the form object with the submitted news
		        if ($f->validate($request)) { // check for valide data.
                        unset($_SESSION["deleteuserform"]);
                        $dataform = $f->getValues(); // extract the data from the form
                        
                        $userrep = new UserRepository();
                        $iduser = $userrep->getUserIdByPseudo($dataform['userpseudo']);
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
                        $tunerep = new TuneRepository();
                        $tunerep->deleteAllTuneUser($iduser);

                        $newsrep = new NewsRepository();
                        $newsrep->deleteAllNewsUser($iduser);

                        $fdrep = new FriendshipRepository();
                        $fdrep->deleteAllFdUser($iduser);

                        if ($userrep->deleteuser($iduser)) {
                            $this->indexAction($request,$f = null, "Success");
                        } else {
                            $this->indexAction($request, $f, "Error");
                        }
                } else {
                    $this->indexAction($request, $f, "Error");
                }
        }

	/**
     * Create the default home page view
     *
     * Display a timeline of the session user, the Profile Card module and the Suggested friends module
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null, $flashbag = null) {
		/*
		* Initialization of the page variables
		*/
		$data = array(); // $data contains all page view data
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

        $data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module
        
        $data['flashbag'] = $flashbag;
                
        if ($f == null) {
			$f = new FormManager("deleteuserform","deleteuserform",UrlRewriting::generateURL("Deleteuser","")); // Form to edit and publish a news
			$f->addField("User pseudo: ","userpseudo","text","","Error");
			$f->addField("Submit ","submit","submit","Delete");	
		}
		$data['deleteuserform'] = $f->createView(); // add the form view in the data page

        $data['reporttitle'] = Translator::translate("Reportin abusive behavior");
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