<?php
/**
 * File containing the NewsongController Class
 *
 */

/**
* NewsongController
*
* NewsongController class display a form to submit new tune and add this new tune to the DB
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class NewsongController extends BaseController
{
	/**
    * Get the new tune form
    * If the form is valid => save the new tune in the DB
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function addnewsongAction(Request &$request) {
        if(!isset($_SESSION["importsongform"])) throw new Exception("\$_SESSION['importsongform'] doesn't exist!");

		$f = unserialize($_SESSION["importsongform"]);
		if ($f->validate($request)) {
            unset($_SESSION["importsongform"]);
			$dataform = $f->getValues();
            $salt = uniqid();
                        
            $tunerep = new TuneRepository();
            if (preg_match('/[^-_a-z0-9.]/iu', $dataform['title'])) // http://stackoverflow.com/questions/18851116/php-regex-for-matching-all-special-characters-included-accented-characters
            {
                $this->indexAction($request, $f , Translator::translate("Special char not allowed in title!"));
            } else {
                if ($tunerep->isTitleTuneuniq($dataform['title'])) {
                    $datetune = date("y-m-d H-i-s");

                    if ($dataform['category'][0] == "other" || $dataform['category'][0] == "autre") {
                        if (!empty($request->getParameter("addcategory")) && strlen($request->getParameter("addcategory")) < 255) {
                            $dataform['category'][0] = htmlspecialchars($request->getParameter("addcategory"));
                        }
                    }

                    /*
                    * Create the tune object
                    */ 
                    $tune = new Tune(null,$dataform['title'], $dataform['composer'], $dataform['category'][0],$datetune, basename($dataform['pdf']['name'],".pdf").$salt.".pdf",$_SESSION['iduser']);
                    $tunerep = new TuneRepository();
                    $tunerep->addTune($tune);

                    /*
                    * Download the pdf file
                    */ 
                    $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pdf']['name']);
                    $img = new ImageManager($filepath);
                    $img->renameMove(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], basename($dataform['pdf']['name'],".pdf").$salt.".pdf"));

                    $this->indexAction($request);
                } else {
                    $this->indexAction($request, $f , Translator::translate("This title is already existing!"));
                }
            }
		} else {
			$this->indexAction($request, $f);
		}
	}
	
	/**
    * Create the default New Song page view
    *
    * Display a form to add a new song, the Profile Card module and the Suggested friends module
    *
    * @param Request &$request Request object.
    * @param FormManager $f optional. contain a form object, default is null.
    * @param String $flashbag return error message.
    * @return void .
    */
	public function indexAction(Request &$request, FormManager $f = null, $flashbag = null) {
        $data = DefaultController::initModule($_SESSION['pseudo']);

        $data['flashbag'] = $flashbag;
        $data['newsongtitle'] = Translator::translate("Add a new song");
        
		/*
		* Create the form to add a new song
		*/
		if ($f == null) {
			$f = new FormManager("importsongform","importsongform",UrlRewriting::generateURL("addNewSong",""));
			$f->addField(Translator::translate("Title: "),"title","text","",Translator::translate("Invalid"));
            $f->addField(Translator::translate("Composer: "),"composer","text","",Translator::translate("Invalid"));
            $f->addField(Translator::translate("Category: "),"category","select",array(array('v' => 'classique','s' => false),array('v' => 'rock','s' => true),array('v' => 'trad','s' => false),array('v' => 'other','s' => false)),"Error",array('id' => 'category'),Translator::translate("Invalid"));
            $f->addField(" ","addcategory","text","",Translator::translate("Invalid"),array(),true);
			$f->addField(Translator::translate("Music score (pdf): "),"pdf","file","",Translator::translate("Invalid"),$attr = array(),$raw = false,"pdf");
			$f->addField("Submit ","submitnewsong","submit",Translator::translate("Import song"),Translator::translate("Error"));	


		}
        $data['importsongform'] = $f->createView();

        $g = new FormManager("musiceditornewsongform","musiceditornewsongform", UrlRewriting::generateURL("musiceditornewsong",""));
        $g->addField(Translator::translate("Title: "),"title","text","",Translator::translate("Invalid"));
        $g->addField(Translator::translate("Composer: "),"composer","text","",Translator::translate("Invalid"));
        $g->addField(Translator::translate("Category: "),"category","select",array(array('v' => 'classique','s' => false),array('v' => 'rock','s' => true),array('v' => 'trad','s' => false),array('v' => 'other','s' => false)),"Error",array('id' => 'category'),Translator::translate("Invalid"));
        $g->addField(" ","addcategory","text","",Translator::translate("Invalid"),array(),true);
        $g->addField("Submit ","submitmusiceditor","submit",Translator::translate("Music editor"),Translator::translate("Error"));
        $data['musiceditornewsongform'] = $g->createView();

		$this->render("NewsongView.html.twig",$data); // create the view
	}
}

?>