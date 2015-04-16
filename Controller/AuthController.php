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

    // public function sendmailAction(Request &$request) {
    //     $f = unserialize($_SESSION["sendmailform"]);
    //     if ($f->validate($request)) {
    //         $msg = "Hello \n Change your password here: <a href='".ConfigReader::get("webroot", "/").UrlRewriting::generateURL("Resetpwd", $key)."'>Confirm</a>";
    //         $mail = new Mailer();
    //         $mail->sendmail("Music session", "musicsession@gmail.com", "Music Session App", "nico.torre.06@gmail.com", "Nico", $msg);
    //     } else {
    //         $this->forgotpwdAction($request, $f);
    //     }
    // }

    // public function forgotpwdAction(Request &$request, FormManager $f = null, $flashbag = null) {
    //     if ($request->existsParameter("lang_fr")) {
    //         $_SESSION['lang'] = "fr";
    //     } else {
    //         $_SESSION['lang'] = "en";
    //     }

    //     $langform = new FormManager("sendmailform","langform",UrlRewriting::generateURL("authpage",""));
    //     $langform->addField("Lang_fr ","lang_fr","submit","Fr");
    //     $langform->addField("Lang_en","lang_en","submit","En");

    //     // the authentification form
    //     if ($f == null) {
    //         $f = new FormManager("authform","authform",UrlRewriting::generateURL("sendmail",""));
    //         $f->addField("E-mail: ","email","email","");
    //         $f->addField("Send e-mail ","submit","submit",Translator::translate("Send e-mail"));    
    //     }

    //     $data = array(); // $data contains all page view data
    //     $data['flashbag'] = $flashbag;
    //     $data['langform'] = $langform->createView(); // the authentification form
    //     $data['sendmailform'] = $f->createView(); // the authentification form

    //     $this->render("PwdView.html.twig",$data); // create the view
    // }

        public function confirmmailAction(Request &$request) {
                $key = $request->getParameter("par")[0];
                $userrep = new UserRepository();
                if ($userrep->confirmmail($key)) {
                    $this->indexAction($request, $f = null, $insc = null, "Your account is registered!");
                } else {
                    $this->indexAction($request, $f = null, $insc = null, "Error during confirmation!");
                }
        }

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
                        unset($_SESSION["langform"]);
                        unset($_SESSION["inscform"]);
			$dataform = $insc->getValues();
                        if (preg_match('/[^-_a-z0-9.]/iu', $dataform['pseudo'])) // http://stackoverflow.com/questions/18851116/php-regex-for-matching-all-special-characters-included-accented-characters
                        {
                            $this->indexAction($request, $f = null, $insc, "Special char not allowed in pseudo!");
                        } else {
                            $iduser = 0;
                            $lang = $_SESSION['lang'];

                            // check no special char in pseudo
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
                                $msg = "Hello \n Confirm your inscription here: <a href='".ConfigReader::get("webroot", "/").UrlRewriting::generateURL("Confirm", $key)."'>Confirm</a>";
                                $mail = new Mailer();
                                $mail->sendmail("Music session", "musicsession@gmail.com", "Music Session App", "nico.torre.06@gmail.com", "Nico", $msg);

                                $this->indexAction($request, $f = null, $insc = null, "Thanks to sign in on Music Score Writer, an e-mail had been sent to you: please confirm your e-mail adress!");
                            } else {
                                $this->indexAction($request, $f = null, $insc, "Pseudo already exist!");
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
                                $flashbag = "Error: invalid pseudo or password!";
				$this->indexAction($request, $f, $insc = null, $flashbag);
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
	public function indexAction(Request &$request, FormManager $f = null, FormManager $insc = null, $flashbag = null) {
            
                if ($request->existsParameter("lang_fr")) {
                    $_SESSION['lang'] = "fr";
                } else {
                    $_SESSION['lang'] = "en";
                }

                $langform = new FormManager("langform","langform",UrlRewriting::generateURL("authpage",""));
                $langform->addField("Lang_fr ","lang_fr","submit","Fr","Error");
                $langform->addField("Lang_en","lang_en","submit","En","Error");

		// the authentification form
		if ($f == null) {
			$f = new FormManager("authform","authform",UrlRewriting::generateURL("authuser",""));
			$f->addField(Translator::translate("Pseudo: "),"pseudo","text","","Invalid pseudo or password!");
			$f->addField(Translator::translate("Password: "),"pwd","password","","Invalid pseudo or password!");
			$f->addField("Sign in ","submit","submit",Translator::translate("Sign in"));	
		}

		// the inscription form
		if ($insc == null) {
			$insc = new FormManager("inscform","inscform",UrlRewriting::generateURL("inscuser",""));
			$insc->addField(Translator::translate("Pseudo: "),"pseudo","text","");
			$insc->addField(Translator::translate("Password: "),"pwdhashed","password","");
			$insc->addField(Translator::translate("Firstname: "),"firstname","text","");
			$insc->addField(Translator::translate("Name: "),"name","text","");
			$insc->addField(Translator::translate("E-mail: "),"email","email","");
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