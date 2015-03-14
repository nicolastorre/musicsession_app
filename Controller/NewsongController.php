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
			$dataform = $f->getValues();
			$datetune = date("y-m-d H-i-s");
			$tune = new Tune(0,$_SESSION['iduser'],$dataform['title'], $dataform['composer'], $dataform['category'], $datetune, $dataform['pdf']);
			$tunerep = new TuneRepository();
			$tunerep->addTune($tune);
			$this->indexAction($request);

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
	public function indexAction(Request &$request, FormManager $f = null) {
		/*
		* Initialization of the page variables
		*/
		$data = array();
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module

		/*
		* Create the form to add a new song
		*/
		if ($f == null) {
			$f = new FormManager("importsongform","importsongform",UrlRewriting::generateURL("addNewSong",""));
			$f->addField("Title: ","title","text","");
			$f->addField("Composer: ","composer","text","");
			$f->addField("Category: ","category","text","");
			$f->addField("pdf score: ","pdf","text","");
			$f->addField("Submit ","submit","submit","Import song");	
		}
		$data['importsongform'] = $f->createView();

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3); // init the Suggested Friends module

		$this->render("NewsongView.html.twig",$data); // create the view
	}

}

?>