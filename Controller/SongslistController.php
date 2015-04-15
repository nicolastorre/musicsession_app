<?php

/******************************************************************************/
// Class HomeController -> manage the home page
// $rss => url du flux rss
// $journal => nom du journal correspondant au flux rss
/******************************************************************************/
class SongslistController extends BaseController
{
	public static function lasttunewidgetAction() {
		$data = array();
		$tunerep = new TuneRepository();
		$tunelist = $tunerep->FindLastTune(2);
		foreach ($tunelist as $tune) {
			$data[] = array("title" => $tune->getTitle(), 
				"composer" => $tune->getComposer(),
				"urltune" => UrlRewriting::generateURL("Tune",$tune->getIdtune()));
		}
		return $data;
	}

	public static function songlistwidgetAction() {
		$data = array();
		$tunerep = new TuneRepository();
		$tunedata = $tunerep->FindUserLikedtuneGroupByCategory($_SESSION['iduser']);
		foreach($tunedata as $category => $tunelist) {
			
			$tmp_tunelist = array();
			foreach ($tunelist as $tune) {
				$tmp_tunelist[] = array('title' => $tune->getTitle(), 'url' => UrlRewriting::generateURL("Tune",$tune->getIdtune()));
			}
			$data[] = array("category" => $category, "tunelist" => $tmp_tunelist);
		}
		return $data;
	}

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

	public function addAction(Request &$request) {
		$pseudo = $request->getParameter("par")[0];
		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
		$tunerep->shareTune($_SESSION['iduser'], $idtune);
//      $request->setParameter($par[0], $pseudo);
		$this->indexAction($request);
	}

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

	// Principal action of the HomeController 
	public function indexAction(Request &$request, FormManager $f = null) {
		$data = array();
		$pseudo = $request->getParameter("par")[0];
		$userrep = new UserRepository();
		$iduser = $userrep->getUserIdByPseudo($pseudo);

		$data['profilcard'] = ProfilController::ProfilCard($pseudo);
		$data['tunelistwidget'] = self::songlistwidgetAction();
		// $data['lasttune'] = self::lasttunewidgetAction();

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
                "addscore" => UrlRewriting::generateURL("Addscore",$tune->getIdtune()),
                "sharetune" => UrlRewriting::generateURL("Share",$tune->getIdtune()),
				"deleteadd" => $tuneaction['deleteadd']);
			$i++;
		}

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);
		
		$this->render("SongslistView.html.twig",$data);
	}

}

?>