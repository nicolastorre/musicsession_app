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
    * Confirm user account by e-mail which containing a link with a key
    *
    * if the key of the e-mail is corresponding to the key of the user in database
    * The account will be confirmed by switching the confirmmail attribute to 1 in the user table
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function confirmmailAction(Request &$request) {
        $key = $request->getParameter("par")[0];
        $userrep = new UserRepository();
        if ($userrep->confirmmail($key)) {
            $this->indexAction($request, $f = null, $insc = null, Translator::translate("Your account is registered!"));
        } else {
            $this->indexAction($request, $f = null, $insc = null, Translator::translate("Error during account confirmation!"));
        }
    }

	/**
    * Inscription of a new user
    *
    * if validated data => save the user data, create the duser directory, send a confirmation mail and load the authentification page (ConfirmInscView) with a authentification form
    * else => reload the Authntification/Inscription page with the previous form
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function inscuserAction(Request &$request) {
        if(!isset($_SESSION["inscform"])) throw new Exception("\$_SESSION['inscform'] doesn't exist!");

		$insc = unserialize($_SESSION["inscform"]);
		if ($insc->validate($request)) {
            unset($_SESSION["langform"]);
            unset($_SESSION["inscform"]);
			$dataform = $insc->getValues();

            // check no special char in pseudo
            if (preg_match('/[^-_a-z0-9.]/iu', $dataform['pseudo'])) // http://stackoverflow.com/questions/18851116/php-regex-for-matching-all-special-characters-included-accented-characters
            {
                $this->indexAction($request, $f = null, $insc, Translator::translate("Special char not allowed in pseudo!"));
            } else {
                $iduser = 0;
                $lang = $_SESSION['lang'];

                $userrep = new UserRepository();
                if (!$userrep->existUserPseudo($dataform['pseudo'])) {
                    $key = uniqid();
                    $user = new User($iduser,$dataform['pseudo'],$dataform['pwdhashed'],$dataform['firstname'],$dataform['name'],$dataform['email'],$lang ,$key);

                    $userrep->addUser($user);

                    // create user directory
                    $userpath = UrlRewriting::generateSRC('userfolder',$dataform['pseudo'],"");
                    if (!is_dir($userpath)) {
                        if(!mkdir($userpath)) {
                            $this->indexAction($request, $f = null, $insc = null, "Error");
                        }
                    }

                    //send mail to confirm inscription
                    $msg = Translator::translate("Hello \n Confirm your inscription here: ")."<a href='".ConfigReader::get("webroot", "/").UrlRewriting::generateURL("Confirm", $key)."'>".Translator::translate("Confirm")."</a>";
                    $mail = new Mailer();
                    $mail->sendmail("Music session", "musicsession@gmail.com", "Music Session App", "nico.torre.06@gmail.com", "Nico", $msg); // put the e-mail user instead of my personal e-mail

                    $this->indexAction($request, $f = null, $insc = null, Translator::translate("Thanks to sign in on Music Session, an e-mail had been sent to you: please confirm your e-mail adress!"));
                } else {
                    $this->indexAction($request, $f = null, $insc, Translator::translate("Pseudo already exists!"));
                }
            }
		} else {
			$this->indexAction($request, $f = null, $insc);
		}
	}

	/**
    * Authentification of a user
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function authuserAction(Request &$request) {
        if(!isset($_SESSION["authform"])) throw new Exception("\$_SESSION['authform'] doesn't exist!");

		$f = unserialize($_SESSION["authform"]);
		if ($f->validate($request)) {
            unset($_SESSION["langform"]);
            unset($_SESSION["authform"]);

            $dataform = $f->getValues();
			$userrep = new UserRepository();
			$authdata = $userrep->authUser($dataform['pseudo'],$dataform['pwd']);
			if ($authdata != false) {
				$_SESSION['iduser'] = $authdata['id_user'];
				$_SESSION['pseudo'] = $authdata['pseudo'];
                $_SESSION['lang'] = $authdata['lang'];

                if ($authdata['access'] == "admin") {
                    $_SESSION['access'] = true;
                }

				$ctrl = new HomeController();
				$ctrl->indexAction($request);

			} else {
                $f->resetPwd();
                $flashbag = Translator::translate("Error: invalid pseudo or password!");
				$this->indexAction($request, $f, $insc = null, $flashbag);
			}
		} else {
			$this->indexAction($request, $f);
		}
	}
	
	/**
    * Display the default authentification page
    *
    * Authentification and Inscription form are available in the page
    *
    * @param Request &$request Request object.
    * @param FormManager $f optional. contain a authentification form object, default is null.
    * @param FormManager $insc optional. contain a inscription form object, default is null.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function indexAction(Request &$request, FormManager $f = null, FormManager $insc = null, $flashbag = null) {
            
        // the language form
        if ($request->existsParameter("lang_fr")) {
            $_SESSION['lang'] = "fr";
        } else {
            $_SESSION['lang'] = "en";
        }

        $langform = new FormManager("langform","langform",UrlRewriting::generateURL("authpage",""));
        $langform->addField("Lang_fr ","lang_fr","submit","Fr",Translator::translate("Error"));
        $langform->addField("Lang_en","lang_en","submit","En",Translator::translate("Error"));

		// the authentification form
		if ($f == null) {
			$f = new FormManager("authform","authform",UrlRewriting::generateURL("authuser",""));
			$f->addField(Translator::translate("Pseudo: "),"pseudo","text","",Translator::translate("Invalid pseudo!"));
			$f->addField(Translator::translate("Password: "),"pwd","password","",Translator::translate("Invalid password!"));
			$f->addField("Sign in ","submit","submit",Translator::translate("Sign in"));	
		}

		// the inscription form
		if ($insc == null) {
			$insc = new FormManager("inscform","inscform",UrlRewriting::generateURL("inscuser",""));
			$insc->addField(Translator::translate("Pseudo: "),"pseudo","text","",Translator::translate("Invalid pseudo!"));
			$insc->addField(Translator::translate("Password: "),"pwdhashed","password","",Translator::translate("Invalid password!"));
			$insc->addField(Translator::translate("Firstname: "),"firstname","text","",Translator::translate("Invalid!"));
			$insc->addField(Translator::translate("Name: "),"name","text","",Translator::translate("Invalid!"));
			$insc->addField(Translator::translate("E-mail: "),"email","email","",Translator::translate("Invalid e-mail!"));
			$insc->addField("Sign up","submit","submit",Translator::translate("Sign up"));	
		}

		$data = array(); // $data contains all page view data
        $data['title'] = "Music session";
        $data['desc'] = Translator::translate("Share your music with your friends!");
        $data['authtitle'] = Translator::translate("Authentification");
        $data['insctitle'] = Translator::translate("Inscription");
        $data['flashbag'] = $flashbag;
        $data['langform'] = $langform->createView(); // the authentification form
		$data['authform'] = $f->createView(); // the authentification form
		$data['inscform'] = $insc->createView(); // the inscription form
        $data['forgottenpwd'] = array('url' => UrlRewriting::generateURL("sendmail",""), 'name' => Translator::translate("Forgotten password?"));

		$this->render("AuthView.html.twig",$data); // create the view
	}
}

?>