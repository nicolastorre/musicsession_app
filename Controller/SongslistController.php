<?php
/**
 * File containing the SongslistController Class
 *
 */

/**
* SongslistController
*
* SongslistController class display a table listing the user's tunes with different button actions
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class SongslistController extends BaseController
{

	/**
	* SongListWidget Module
	* Display a list by category of tunes for the session user
	* Get all informations needed by the songlistwidget template
	*
	* @return array $data elements contain needed informations for the songlistwidget template.
	*/
	public static function songlistwidgetAction() {
		$data = array();
		$tunerep = new TuneRepository();
		$tunedata = $tunerep->FindUserLikedtuneGroupByCategory($_SESSION['iduser']);
                $data["title"] = Translator::translate("Tunes list");
		foreach($tunedata as $category => $tunelist) {
			$tmp_tunelist = array();
			foreach ($tunelist as $tune) {
				$tmp_tunelist[] = array('title' => $tune->getTitle(), 'url' => UrlRewriting::generateURL("Tune",$tune->getIdtune()));
			}
			$data['tune'][] = array("category" => $category, "tunelist" => $tmp_tunelist);
		}
		return $data;
	}

	/**
	* delete a tune from the user tunebook
	* delete the likedtune tuple in the DB for the current user
	* delete the pdf file if the user has contributed to a version of this tune
	* delete the tune tuple in the DB if the tune is no more liked by any users
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function deleteAction(Request &$request) {
		$pseudo = $request->getParameter("par")[0];
		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
		$tune = $tunerep->findTuneById($idtune);
		if ($tune != false) {
			// the user has shared some versions of the tune so ...
			if (is_array($tune->getForkedusers()) && (in_array($_SESSION['iduser'],$tune->getForkedusers()))) {
				$forkedusers = $tune->getForkedusers();
                $pdfscore = $tune->getPdfscore();
				for ($i=0; $i<count($forkedusers); $i++) {
					if($forkedusers[$i] == $_SESSION['iduser']) {
						$file = UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'],$pdfscore[$i]);
                        if (file_exists($file)) {
                        	chmod($file,0755);
                            unlink($file);
                        }
					}
				}
				$tunerep->deleteScoreTuneByUser($idtune,$_SESSION['iduser']);
			}
			// remove from likedtube for the current user
			$tunerep->deleteTuneForUser($_SESSION['iduser'], $idtune);
			// the user is the last to have this tune so deleteAll ...
			if($tunerep->nbLikedTune($idtune) == 0) {
				$tunerep->deleteTuneForAll($idtune);
			}			
		}
		$request->setParameter("par", array($pseudo));
		$this->indexAction($request);
	}

	/**
	* Add action to add a tune to the tunebook of a user 
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function addAction(Request &$request) {
		$pseudo = $request->getParameter("par")[0];
		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
		$tunerep->shareTune($_SESSION['iduser'], $idtune);
//      $request->setParameter($par[0], $pseudo);
		$this->indexAction($request);
	}

	/**
	* Share action to share a hashtag of the tune on the user news timeline 
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function shareAction(Request &$request) {
		$idtune = $request->getParameter("par")[0];
		
        $tunerep = new TuneRepository();
        $titletune = $tunerep->findTitleTuneById($idtune);

        $date_news = date("y-m-d H-i-s");
		$datanews = "<a href='Tune/index/".$idtune."' class='hashtag'>#".$titletune."</a>";
		$news = new News($_SESSION['iduser'],$_SESSION['pseudo'],$date_news,$datanews); // create the news object
		$newsrep = new NewsRepository();
		$newsrep->addNews($news); // add the submitted news
		$ctrl = new HomeController();
		$ctrl->indexAction($request); // reload the default home page
	}

	/**
    * Create the default Songslist page view (tunebook)
    *
    * Display a timeline of the session user, the Profile Card module and the Suggested friends module
    *
    * @param Request &$request Request object.
    * @return void .
    */
	public function indexAction(Request &$request) {
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		ProfilController::checkAllowedProfileUser($request, $iduser); // protection user profile

		$data = DefaultController::initModule($pseudo);
                
        $data['tunelistheader']['title'] = Translator::translate("Title");
        $data['tunelistheader']['composer'] = Translator::translate("Composer");
        $data['tunelistheader']['category'] = Translator::translate("Category");
        $data['tunelistheader']['date'] = Translator::translate("Date");

		$tunerep = new TuneRepository();
		$tunelist = $tunerep->FindUserLikedtune($iduser);
		$i = 1;
		foreach ($tunelist as $tune) {
			if ($i%2 == 0) {
				$class = "item-even";
			} else {
				$class = "item-odd";
			}
                        
            $tuneaction = array();
			if ($_SESSION['pseudo'] == $pseudo && $tunerep->checkTuneLikedByUser($_SESSION['iduser'], $tune->getIdtune())) {
				$tuneaction['deleteadd'] = array('class' => 'delete', 'url' => UrlRewriting::generateURL("Delete",$pseudo."/".$tune->getIdtune()));
			} 
			elseif (!$tunerep->checkTuneLikedByUser($_SESSION['iduser'], $tune->getIdtune())) {
				$tuneaction['deleteadd'] = array('class' => 'add', 'url' => UrlRewriting::generateURL("Add",$pseudo."/".$tune->getIdtune()));
			} else {
				$tuneaction['deleteadd'] = array('class' => '', 'url' => "#");
			}
                        
			$data['tunelist'][] = array("class" => $class,
				"idtune" => $tune->getIdtune(), 
				// "iduser" => $tune->getForkedusers(), 
				"title" => $tune->getTitle(), 
				"composer" => $tune->getComposer(), 
				"category" => $tune->getCategory(), 
				"datetune" => Pubdate::printDate($tune->getDatetune()),  
				"pdftune" => $tune->getPdfscore(),
				"urltune" => UrlRewriting::generateURL("Tune",$pseudo."/".$tune->getIdtune()),
                "addscore" => UrlRewriting::generateURL("Addscore",$pseudo."/".$tune->getIdtune()),
                "sharetune" => UrlRewriting::generateURL("Share",$tune->getIdtune()),
				"deleteadd" => $tuneaction['deleteadd']);
			$i++;
		}
		
		$this->render("SongslistView.html.twig",$data);
	}
}

?>