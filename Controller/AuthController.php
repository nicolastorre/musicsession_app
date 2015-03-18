<?php
/**
 * File containing the AuthController Class
 *
 */

/**
 * AuthController
 *
 * AuthController to log in or sign in the website
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class AuthController extends BaseController
{
	/**
     * Inscription of a new user
     *
     * if validated data => save the user data and load the authntification page (ConfirmInscView) with a authntification form
     * else => reload the Authntification/Inscription page with the previous form
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function inscuserAction(Request &$request) {
		$insc = unserialize($_SESSION["inscform"]);
		if ($insc->validate($request)) {
			$dataform = $insc->getValues();
			$iduser = 0;
			$lang = "fr";

			// check no special char in pseudo

			$user = new User($iduser,$dataform['pseudo'],$dataform['pwdhashed'],$dataform['firstname'],$dataform['name'],$dataform['email'],$lang);
			$userrep = new UserRepository();
			$userrep->addUser($user);

			// create user directory
			$userpath = UrlRewriting::generateSrcUser($dataform['pseudo'],"");
			if (!is_dir($userpath) && mkdir($userpath)) {
				// create the default profile_pic.png
			}

			//send mail to confirm inscription

			$data = array();
			$data['message']  = "Thanks to sign in on Music Score Writer, an e-mail had been sent to you: please confirm your e-mail adress!";

			$f = new FormManager("authform","authform",UrlRewriting::generateURL("authuser",""));
			$f->addField("Pseudo: ","pseudo","text","");
			$f->addField("Password: ","pwd","text","");
			$f->addField("Log in ","submit","submit","Log in");	

			$data['authform'] = $f->createView();

			$this->render("ConfirmInscView.html.twig",$data);

			
		} else {
			$this->indexAction($request, $f = null, $insc);
		}
	}

	/**
     * Authentificaiton of a user
     *
     * @param Request &$request Request object.
     * @return void .
     */
	public function authuserAction(Request &$request) {
		$f = unserialize($_SESSION["authform"]);
		if ($f->validate($request)) {
			$dataform = $f->getValues();
			$userrep = new UserRepository();
			$authdata = $userrep->authUser($dataform['pseudo'],$dataform['pwd']);
			if ($authdata != false) {
				$_SESSION['iduser'] = $authdata['id_user'];
				$_SESSION['pseudo'] = $authdata['pseudo'];

				$ctrl = new HomeController();
				$ctrl->indexAction($request);
			} else {
				$this->indexAction($request, $f);
			}
		} else {
			$this->indexAction($request, $f);
		}
	}
	
	/**
     * Display the default authentification page
     *
     * Authntification and Inscription form are available in the page
     *
     * @param Request &$request Request object.
     * @param FormManager $f optional. contain a authentification form object, default is null.
     * @param FormManager $g optional. contain a inscription form object, default is null.
     * @return void .
     */
	public function indexAction(Request &$request, FormManager $f = null, FormManager $insc = null) {

		// the authentification form
		if ($f == null) {
			$f = new FormManager("authform","authform",UrlRewriting::generateURL("authuser",""));
			$f->addField("Pseudo: ","pseudo","text","");
			$f->addField("Password: ","pwd","text","");
			$f->addField("Log in ","submit","submit","Log in");	
		}

		// the inscription form
		if ($insc == null) {
			$insc = new FormManager("inscform","inscform",UrlRewriting::generateURL("inscuser",""));
			$insc->addField("Pseudo: ","pseudo","text","");
			$insc->addField("Password: ","pwdhashed","text","");
			$insc->addField("Firstname: ","firstname","text","");
			$insc->addField("Name: ","name","text","");
			$insc->addField("E-mail: ","email","email","");
			$insc->addField("Sign in ","submit","submit","Sign in");	
		}

		$data = array(); // $data contains all page view data
		$data['authform'] = $f->createView(); // the authentification form
		$data['inscform'] = $insc->createView(); // the inscription form

		$this->render("AuthView.html.twig",$data); // create the view
	}
}

?>