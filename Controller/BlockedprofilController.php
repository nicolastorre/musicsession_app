<?php
/**
 * File containing the BlockedprofilController Class
 *
 */

/**
* BlockedprofilController
*
* BlockedprofilController class is instanciated when a user try to access to an not allowed user profile
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class BlockedprofilController extends BaseController
{
	
	/**
    * Create the default blockedprofil page view
    *
    * Display the main module, so just the profilCardModule of the user profile will be display
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function indexAction(Request &$request) {
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);
		$data = DefaultController::initModule($pseudo);

        $data['flashbag'] = Translator::translate("You're not friend with this user!");

		$this->render("ProfilView.html.twig",$data);
	}
}

?>