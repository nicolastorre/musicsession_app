<?php
/**
 * File containing the MusiceditorController Class
 *
 */

/**
* MusiceditorController
*
* MusiceditorController class display the music editor
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class MusiceditorController extends BaseController
{
    /**
    * Check the tune form
    * If the form is valid => redirect to Music editor
    * else redirect to the tune form
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function musiceditornewsongAction(Request &$request) {
        if(!isset($_SESSION["musiceditornewsongform"])) throw new Exception("\$_SESSION['musiceditornewsongform'] doesn't exist!");

        $f = unserialize($_SESSION["musiceditornewsongform"]);
        if ($f->validate($request)) {
            unset($_SESSION["musiceditornewsongform"]);
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

                    $f = new FormManager("importsongform","importsongform",UrlRewriting::generateURL("savepdftune",""));
                    $f->addField(Translator::translate("Title: "),"title","text","",Translator::translate("Invalid"));
                    $f->addField(Translator::translate("Composer: "),"composer","text","",Translator::translate("Invalid"));
                    $f->addField(Translator::translate("Category: "),"category","text","",Translator::translate("Invalid"));
                    $f->addField(Translator::translate("Music score (pdf): "),"pdf","text","",Translator::translate("Invalid"));
                    $f->addField("Submit ","submit","submit",Translator::translate("Import song"),Translator::translate("Error"));  

                    $request->setParameter("par",array($dataform['title'], $dataform['composer'], $dataform['category'][0]));
                    $this->indexAction($request);
                } else {
                    $ctrl = new NewsongController();
                    $ctrl->indexAction($request, $f , Translator::translate("This title is already existing!"));
                }
            }
        } else {
            $ctrl = new NewsongController();
            $ctrl->indexAction($request, $f);
        }
    }

    /**
    * Save the tune
    * send score info to the form
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function savepdftuneAction(Request &$request) {
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

                    /*
                    * Create the tune object
                    */ 
                    $newfilename = basename($dataform['pdf'],".pdf").$salt.".pdf";
                    $tune = new Tune(null,$dataform['title'], $dataform['composer'], $dataform['category'],$datetune, $newfilename,$_SESSION['iduser']);
                    $tunerep = new TuneRepository();
                    $tunerep->addTune($tune);

                    /*
                    * Download the pdf file
                    */ 
                    $filepath = UrlRewriting::generateSRC("tmp","",$dataform['pdf']);
                    $img = new ImageManager($filepath);
                    $img->renameMove(UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], $newfilename));

                    $ctrl = new SongslistController();
                    $request->setParameter("par",array($_SESSION['pseudo']));
                    $ctrl->indexAction($request);
                } else {
                    $this->indexAction($request, $f , Translator::translate("This title is already existing!"));
                }
            }
        } else {
            $this->indexAction($request, $f);
        }
    }

    /**
    * launch the downloading of the pdf score
    *
    * @return void .
    */
    public function downloadscoreAction() {
        $request = new Request();
        $title = $request->getParameter("par")[0];
        $file = UrlRewriting::generateSRC("tmp","",$title.".pdf");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
        header('Content-Length: ' . filesize($file));
        readfile($file);
        chmod($file,0755);
        unlink($file); 
    }

    /**
    * Export the score from the music editor in pdf
    * request via AJAX
    *
    * @return void .
    */
    public function scorepdfAction() {
        $file = UrlRewriting::generateSRC("tmp","",$_POST['title']);
        $pdf = new mPDF();
        $pdf->WriteHTML("<!DOCTYPE html><html><body>".$_POST['score']."</body></html>");
        $pdf->Output($file,"F");
    }
	
	/**
    * Create the default music editor page view
    *
    * Display the music editor to edit music score and produce pdf file
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function indexAction(Request &$request) {
		$data = array();

        $data['scoretitle'] = $request->getParameter("par")[0];
        $data['scorecomposer'] = $request->getParameter("par")[1];
        $data['scorecategory'] = $request->getParameter("par")[2];

		$this->render("MusicEditorView.html.twig",$data);
	}
}

?>