<?php
/**
 * File containing the ParametersController Class
 *
 */

/**
* ParametersController
*
* ParametersController class display forms to modify user's settings
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class ParametersController extends BaseController
{

    /**
    * Get the abusive behavior report form
    * if the form is valid => save the report in the DB
    * then display the parameters page
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function signalAction(Request &$request) {
        if(!isset($_SESSION["signalform"])) throw new Exception("\$_SESSION['signalform'] doesn't exist!");

        $j = unserialize($_SESSION["signalform"]); // get back the form object with the submitted news
                
        if ($j->validate($request)) { // check for valide data.
            unset($_SESSION["signalform"]);
                        
            $dataform = $j->getValues(); // extract the data from the form
            $date_report = date("y-m-d H-i-s");

            $report = new report($_SESSION['iduser'],$_SESSION['pseudo'],$date_report,$dataform['signal']); // create the news object
            $reportrep = new ReportRepository();
            $reportrep->addReport($report); // add the submitted news
            $this->indexAction($request, $f = null, $g = null, $h = null, $flashbag = Translator::translate("Send with success!")); // reload the default home page
        } else {
            $this->indexAction($request, $f = null, $g = null, $h = null, $flashbag = null, $i = null, $j);
        }
    }

    /**
    * Display two links to confirm the suppression of the user account 
    * or cancel the suppression
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function askdeleteaccountAction(Request &$request, FormManager $f = null) {
        $data = DefaultController::initModule($_SESSION['pseudo']);
        $data['ask'] = Translator::translate("Are you sure to remove your account?");
        $data['deleteaccount'] = array('url' => UrlRewriting::generateURL("deleteaccount", ""), 'name' => Translator::translate("Yes"));
        $data['redirect'] = array('url' => UrlRewriting::generateURL("Parameters", ""), 'name' => Translator::translate("No"));
        $this->render("AskDeleteAccountView.html.twig",$data);
    }

    /**
    * delete the user's account and all user's informations
    * then if deleting succeded => display the authentification page
    * else => display the parameters page with an error message
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function deleteaccountAction(Request &$request) {
        $userrep = new UserRepository();
        $iduser = $userrep->getUserIdByPseudo($_SESSION['pseudo']);
        $dir = UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'],"");

        // Remove all user's files and the user directory
        chmod($dir,0755);
        $files = scandir($dir);                       
        foreach ($files as $i) {
            if ($i != "." && $i != "..") {
                chmod($dir.$i,0755);
                unlink($dir.$i);    
            }
        }
        rmdir($dir);

        // Remove all user's tunes
        $tunerep = new TuneRepository();
        $tunerep->deleteAllTuneUser($iduser);

        // Remove all user's news
        $newsrep = new NewsRepository();
        $newsrep->deleteAllNewsUser($iduser);

        // Remove all user's friendship
        $fdrep = new FriendshipRepository();
        $fdrep->deleteAllFdUser($iduser);

        // Remove the user
        if ($userrep->deleteuser($iduser)) {
            $ctrl = new AuthController();
            $ctrl->indexAction($request);
        } else {
            $this->indexAction($request, $f = null, $g = null, $h = null, Translator::translate("Error during deleting your accounts!"));
        }
    }

    /**
    * log out the user by unsetting the session user variables
    * then display the authentification page
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function logoutAction(Request &$request) {
        unset($_SESSION['iduser']);
        unset($_SESSION['pseudo']);
        $ctrl = new AuthController();
        $ctrl->indexAction($request);
    }

    /**
    * if the form is valid => get and save the new password in the DB
    * else display the parameters page
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function updatepwdAction(Request &$request) {
        if(!isset($_SESSION["updatepwd"])) throw new Exception("\$_SESSION['updatepwd'] doesn't exist!");

        $h = unserialize($_SESSION["updatepwd"]);
        if ($h->validate($request)) {
            unset($_SESSION["updatepwd"]);
            $dataform = $h->getValues();
            if ($dataform['pwd'] == $dataform['confirmpwd']) {
                $userrep = new UserRepository();
                $user = $userrep->findUserById($_SESSION['iduser']);
                $user->setPwdhashed(password_hash($dataform['pwd']));

                $userrep->updateUser($user);
                
                $this->indexAction($request);
            } else {
                $this->indexAction($request, $f = null, $g = null, $h);
            }
        }  else {
	           $this->indexAction($request, $f = null, $g = null, $h);
        }
    }

    /**
    * if the form is valid => get, download and save the new profile photo in the DB
    * else display the parameters page
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function updatephotoAction(Request &$request) {
        if(!isset($_SESSION["updatephoto"])) throw new Exception("\$_SESSION['updatephoto'] doesn't exist!");

        $g = unserialize($_SESSION["updatephoto"]);
        if ($g->validate($request)) {
            unset($_SESSION["updatephoto"]);
            $dataform = $g->getValues();

            // Convert the photo in profile photo by renaming, moving in the user directory and resizing the photo
            $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pic']['name']);
            $img = new ImageManager($filepath);
            $img->convertInProfilePic(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'],"profile_pic.png"));
            
            $this->indexAction($request);
        } else {
			$this->indexAction($request, $f = null, $g);
        }
    }

    /**
    * if the form is valid => get, and update the basic user settings in the DB
    * else display the parameters page
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function updateparametersAction(Request &$request) {
        if(!isset($_SESSION["parametersform"])) throw new Exception("\$_SESSION['parametersform'] doesn't exist!");

		$f = unserialize($_SESSION["parametersform"]);
		if ($f->validate($request)) {
            unset($_SESSION["parametersform"]);
			$dataform = $f->getValues();
			
			// test if pseudo is unique in the DB
            if (preg_match('/[^-_a-z0-9.]/iu', $dataform['pseudo']))
            {
                $this->indexAction($request, $f = null, $g = null, $h = null, Translator::translate("Special char not allowed in pseudo!"));
            } else {
                $userrep = new UserRepository();
                if ($dataform['pseudo']==$_SESSION['pseudo'] || !$userrep->existUserPseudo($dataform['pseudo'])) {
                    $user = $userrep->findUserById($_SESSION['iduser']);
                    $user->setpseudo($dataform['pseudo']);
                    $_SESSION['pseudo'] = $dataform['pseudo'];
                    $user->setEmail($dataform['email']);
                    $user->setLang($dataform['lang'][0]);
                    $_SESSION['lang'] = $dataform['lang'][0];
                    $userrep->updateUser($user);
                    $this->indexAction($request);
                } else {
                    $this->indexAction($request, $f = null, $g = null, $h = null, Translator::translate("Pseudo already exist!"));
                }
            }
		} else {
			$this->indexAction($request, $f);
		}
	}

    /**
    * Create the default parameters page view
    *
    * Display multiple forms to change user settings, the Profile Card module and the Suggested friends module
    *
    * @param Request &$request Request object.
    * @param FormManager $f optional. form to modify the basic settings, default is null.
    * @param FormManager $g optional. form to modify the profile photo, default is null.
    * @param FormManager $h optional. form to modify the password, default is null.
    * @param String $flashbag return error message.
    * @param FormManager $j optional. form to send a abusive behavior report to the Admin, default is null.
    * @return void .
    */
	public function indexAction(Request &$request, FormManager $f = null, FormManager $g = null, FormManager $h = null, $flashbag = null, FormManager $j = null) {
        $data = DefaultController::initModule($_SESSION['pseudo']);
                
        $data['flashbag'] = $flashbag;
        $data['accounttitle'] = Translator::translate("Account");
        $data['accountdesc'] = Translator::translate("Change your basic settings.");
        $data['profilephototitle'] = Translator::translate("Profile photo");
        $data['profilephotodesc'] = Translator::translate("Change your profile photo.");
        $data['passwordtitle'] = Translator::translate("Password");
        $data['passworddesc'] = Translator::translate("Change your password settings.");
        $data['reportingtitle'] = Translator::translate("Reporting");
        $data['reportingdesc'] = Translator::translate("Reporting abusive behavior.");
        $data['removetitle'] = Translator::translate("Remove your account");
        $data['removedesc'] = Translator::translate("Delete definitely your account and your informations.");

		if ($f == null) {
            $userrep = new UserRepository();
			$user = $userrep->findUserById($_SESSION['iduser']);
			$f = new FormManager("parametersform","parametersform",UrlRewriting::generateURL("updateparameters",""));
			$f->addField(Translator::translate("Pseudo: "),"pseudo","text",$user->getPseudo());
			$f->addField(Translator::translate("E-mail: "),"email","email",$user->getEmail());
			$languser = $user->getLang();
			$langvalues = array(array('v' => "fr",'s' => false),array('v' => "en",'s' => false));
			for ($i=0; $i<count($langvalues);$i++) {
				if ($langvalues[$i]['v'] == $languser) {
					$langvalues[$i]['s'] = true;
				}
			}
			$f->addField(Translator::translate("Language: "),"lang","select",$langvalues);
			$f->addField("Submit ","submit","submit",Translator::translate("Update"));	
		}
		$data['parametersform'] = $f->createView();

		if ($g == null) {
			$g = new FormManager("updatephoto","updatephoto",UrlRewriting::generateURL("updatephoto",""));
            $g->addField(Translator::translate("Profile photo: "),"pic","file","");
			$g->addField("Submitphoto","submit","submit",Translator::translate("Upload your photo"));	
		}
		$data['updatephoto'] = $g->createView();
                
        if ($h == null) {
			$h = new FormManager("updatepwd","updatepwd",UrlRewriting::generateURL("updatepassword",""));
            $h->addField(Translator::translate("Password: "),"pwd","text","");
            $h->addField(Translator::translate("Confirm your password: "),"confirmpwd","text","");
			$h->addField("Submitphoto","submit","submit",Translator::translate("Update"));	
		}
		$data['updatepwd'] = $h->createView();

        if ($j == null) {
            $j = new FormManager("signalform","signalform",UrlRewriting::generateURL("signal","")); // Form to edit and publish a news
            $j->addField("","signal","textarea","",Translator::translate("Invalid"));
            $j->addField("Submit ","submit","submit",Translator::translate("Send"));   
        }
        $data['signalform'] = $j->createView(); // add the form view in the data page
                
        $data['logout'] = array('url' => UrlRewriting::generateURL("logout", ""), 'value' => Translator::translate("log out"));
        $data['deleteaccount'] = array('url' => UrlRewriting::generateURL("askdeleteaccount", ""), 'value' => Translator::translate("Delete account"));
                
		$this->render("ParametersView.html.twig",$data);
	}
}

?>