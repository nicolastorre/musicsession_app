<?php
/**
 * File containing the NewsongController Class
 *
 */

/**
 * NewsongController
 *
 * NewsongController class manage the New Song page features
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class NewsongController extends BaseController
{
	/**
     * Get, treat and save the new submitted song 
     * then if no errors => reload the default home page 
     * else => reload the new song page with the previous form
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function addnewsongAction(Request &$request) {
		$f = unserialize($_SESSION["importsongform"]);
		if ($f->validate($request)) {
                        unset($_SESSION["importsongform"]);
			$dataform = $f->getValues();
                        $salt = uniqid();
                        
                        $tunerep = new TuneRepository();
                        if (preg_match('/[^-_a-z0-9.]/iu', $dataform['title'])) // http://stackoverflow.com/questions/18851116/php-regex-for-matching-all-special-characters-included-accented-characters
                        {
                            $this->indexAction($request, $f , "Special char not allowed in title!");
                        } else {
                            if ($tunerep->isTitleTuneuniq($dataform['title'])) {
                                $datetune = date("y-m-d H-i-s");

                                if ($dataform['category'][0] == "other" || $dataform['category'][0] == "autre") {
                                    if (!empty($request->getParameter("addcategory")) && strlen($request->getParameter("addcategory")) < 255) {
                                        $dataform['category'][0] = htmlspecialchars($request->getParameter("addcategory"));
                                    }
                                }

                                $tune = new Tune(null,$dataform['title'], $dataform['composer'], $dataform['category'][0],$datetune, basename($dataform['pdf']['name'],".pdf").$salt.".pdf",$_SESSION['iduser']);
                                $tunerep = new TuneRepository();
                                $tunerep->addTune($tune);

                                $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pdf']['name']);
                                $img = new ImageManager($filepath);
                                $img->renameMove(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], basename($dataform['pdf']['name'],".pdf").$salt.".pdf"));

                                $this->indexAction($request);
                            } else {
                                $this->indexAction($request, $f , "This title is already existing!");
                            }
                        }
		} else {
			$this->indexAction($request, $f);
		}
	}
	
	// Principal action of the HomeController 
	/**
     * Create the default New Song page view
     *
     * Display a form to add a new song, the Profile Card module and the Suggested friends module
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null, $flashbag = null) {
		/*
		* Initialization of the page variables
		*/
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

                $data['flashbag'] = $flashbag;
		/*
		* Create the form to add a new song
		*/
		if ($f == null) {
			$f = new FormManager("importsongform","importsongform",UrlRewriting::generateURL("addNewSong",""));
			$f->addField("Title: ","title","text","");
                        $f->addField("Composer: ","composer","text","");
                        $f->addField("Category: ","category","select",array(array('v' => 'classique','s' => false),array('v' => 'rock','s' => true),array('v' => 'trad','s' => false),array('v' => 'other','s' => false)),"Error",array('id' => 'category'));
                        $f->addField(" ","addcategory","text","","Error",array('id' => 'other'),true);
			$f->addField("pdf score: ","pdf","file","");
			$f->addField("Submit ","submit","submit","Import song","Error",array('id' => 'submit'));	
		}
		$data['importsongform'] = $f->createView();

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3); // init the Suggested Friends module

		$this->render("NewsongView.html.twig",$data); // create the view
	}

}

?>