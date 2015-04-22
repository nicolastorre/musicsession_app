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
    * launch the downloading of the midi score
    *
    * @return void .
    */
    public function downloadmidiAction() {
        $request = new Request();
        $title = $request->getParameter("par")[0];
        $file = UrlRewriting::generateSRC("tmp","",$title.".mid");
        header("Content-Type: audio/midi");
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($file));
        readfile($file);
        chmod($file,0755);
        unlink($file);
        exit;
    }

    /**
    * Check the tune form
    * If the form is valid => redirect to Music editor
    * else redirect to the tune form
    *
    * @param Request &$request Request object.
    * @return void .
    */
    public function createmidiAction() {
        $score = json_decode($_POST['score'],true);
        $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        $file = UrlRewriting::generateSRC("tmp","",$_POST["title"]);
$miditxt = "
MFile 1 5 480
MTrk
0 SeqSpec 00 00 41
0 Meta Text 'Sequence'
0 SMPTE 96 0 10 0 0
0 TimeSig 4/4 24 8
0 Tempo 500000
0 Meta TrkEnd
TrkEnd
MTrk
0 Meta Text 'Acoustic Grand Piano'
0 Par ch=13 c=6 v=0
0 Par ch=13 c=7 v=100
0 Par ch=13 c=64 v=0
0 Pb ch=13 v=8192
0 PrCh ch=13 p=16\n";
        $delay = 0;
        $t="";
        foreach($score as $note) {
            $miditxt .= $delay." On ch=13 n=".$note['midi']." v=100 \n";
            $delay += $note['duration']*120*4;
            $miditxt .= $delay." Off ch=13 n=".$note['midi']." v=100 \n";
        }
        $miditxt .= "$delay Meta TrkEnd \nTrkEnd";
        // fwrite($myfile, print_r($score,true));
        // fwrite($myfile, print_r($score["note_0"]["midi"],true));
        fwrite($myfile, $miditxt);
        fclose($myfile);

        $midi = new Midi();
        $midi->importTxt($miditxt);
        $midi->saveMidFile($file.'.mid');

    }

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
    public function downloadscoreAction(Request &$request) {
        $title = $request->getParameter("par")[0];
        $file = UrlRewriting::generateSRC("tmp","",$title.".pdf");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
        header('Content-Length: ' . filesize($file));
        readfile($file);
        chmod($file,0755);
        unlink($file);
        exit;
    }

    /**
    * Export the score from the music editor in pdf
    * request via AJAX
    *
    * @return void .
    */
    public function scorepdfAction() {
        $file = UrlRewriting::generateSRC("tmp","",$_POST["title"]);
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
        $pseudo = $_SESSION['pseudo'];
        $userrep = new UserRepository();
        $iduser = $userrep->getUserIdByPseudo($pseudo);
        $invitationrep = new InvitationRepository();
        $data['minibox']['notification'] = $invitationrep->getNonReadInvitation($iduser)['nb'];
        $msgrep = new MessageRepository();
        $data['minibox']['messages'] = $msgrep->getNonReadMessages($iduser)['nb'];

        $data['scoretitle'] = $request->getParameter("par")[0];
        $data['scorecomposer'] = $request->getParameter("par")[1];
        $data['scorecategory'] = $request->getParameter("par")[2];

		$this->render("MusicEditorView.html.twig",$data);
	}
}

?>