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


		$this->render("BackofficeView.html.twig",$data); // create the view
	}

}

?>