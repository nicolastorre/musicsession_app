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
class HomeController extends BaseController
{

	/**
     * Get, treat and save the submitted news 
     * then if no errors => reload the default home page 
     * else => reload the home page with the previous form
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function addnewsAction(Request &$request) {
		$f = unserialize($_SESSION["editnewsform"]); // get back the form object with the submitted news
                
		if ($f->validate($request)) { // check for valide data.
                        unset($_SESSION["editnewsform"]);
                        
			$dataform = $f->getValues(); // extract the data from the form
			$date_news = date("y-m-d H-i-s");

			$news = new News($_SESSION['iduser'],$_SESSION['pseudo'],$date_news,$dataform['news']); // create the news object
			$newsrep = new NewsRepository();
			$newsrep->addNews($news); // add the submitted news
			$this->indexAction($request); // reload the default home page
		} else {
			$this->indexAction($request, $f);
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
	public function indexAction(Request &$request, FormManager $f = null) {
		/*
		* Initialization of the page variables
		*/
		$data = array(); // $data contains all page view data
		$pseudo = $_SESSION['pseudo'];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

                $data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();

		if ($f == null) {
			$f = new FormManager("editnewsform","editnewsform",UrlRewriting::generateURL("addNews","")); // Form to edit and publish a news
			$f->addField("","news","textarea","","Error");
			$f->addField("Submit ","submit","submit","News");	
		}
		$data['newsform'] = $f->createView(); // add the form view in the data page

		/*
		* Create the timeline of news for the session user
		*/
		$newsrep = new NewsRepository();
		$newslist = $newsrep->findAllNewsFriendsUser($iduser);
		foreach ($newslist as $news) {
			$data['newslist'][] = array("url" => UrlRewriting::generateURL("Profil",$news->getUserpseudo()), "user" => $news->getUserpseudo(),
					"profilephoto" => UrlRewriting::generateSRC("userfolder", $news->getUserpseudo(),"profile_pic.png", "../default/profile_pic.png"),
					"pubdate" => Pubdate::printDate($news->getPubdate()),
					"content" => $news->getContent());
		}

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3); // init the Suggested Friends module

                $this->render("HomeView.html.twig",$data); // create the view
	}

}

?>