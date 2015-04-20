<?php
/**
 * File containing the SearchController Class
 *
 */

/**
 * SearchController
 *
 * SearchController class manage the search page features, to display a list of results from a search in the menu search widget
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class SearchController extends BaseController
{

     /**
     * Create the default search page view
     *
     * Display the result of the search of the session user, the Profile Card module and the Suggested friends module
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null) {
        $data = DefaultController::initModule($_SESSION['pseudo']);

		$f = unserialize($_SESSION["searchform"]); // get back the form object with the submitted news
		if ($f->validate($request)) { // check for valide data
			$dataform = $f->getValues(); // extract the data from the form
            $data['error'] = "";
            
            $userrep = new UserRepository();
            $userpseudolist = $userrep->searchUser($dataform['inputsearch']);
            if ($userpseudolist) {
                foreach ($userpseudolist as $userpseudo) {
                    if ($userpseudo != $_SESSION['pseudo']) {
                        $data['search'][] = array("url" => UrlRewriting::generateURL("Profil",$userpseudo), "pseudo" => $userpseudo,
                                "profilephoto" => UrlRewriting::generateSRC("userfolder", $userpseudo,"profile_pic.png", "../default/profile_pic.png"),);
                    }
                }
            } else {
                $data['error'] = Translator::translate("No matching results!");
            }
		} else {
                    $data['error'] = Translator::translate("No matching results!");
		}
		$this->render("Search.html.twig",$data); // create the view
	}
}

?>