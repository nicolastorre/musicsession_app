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
		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
                $tune = $tunerep->findTuneById($idtune);
                if ($tune != false) {
                    if ($tune->getIduser() == $_SESSION['iduser']) {
                        // delete the pdf file
                        $file = UrlRewriting::generateSRC("userfolder", $_SESSION['pseudo'], $tune->getPdf());
                        if (file_exists($file)) {
                            unlink($file);
                        }
                        // delete each tuple with fk_tune in the likedtune table
                        $tunerep->deleteTuneForAll($idtune);
                    } else {
                        $tunerep->deleteTuneForUser($_SESSION['iduser'], $idtune);
                    }
                }
//                $request->setParameter($par[0], $pseudo);
		$this->indexAction($request);
	}

	public function addAction(Request &$request) {
		$idtune = $request->getParameter("par")[1];
		$tunerep = new TuneRepository();
		$tunerep->shareTune($_SESSION['iduser'], $idtune);
//                $request->setParameter($par[0], $pseudo);
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
                        
                        $tuneaction['share'] = array();

			$data['tunelist'][] = array("class" => $class,
				"idtune" => $tune->getIdtune(), 
				"iduser" => $tune->getIduser(), 
				"title" => $tune->getTitle(), 
				"composer" => $tune->getComposer(), 
				"category" => $tune->getCategory(), 
				"datetune" => $tune->getDatetune(),  
				"pdftune" => $tune->getPdf(),
				"urltune" => UrlRewriting::generateURL("Tune",$tune->getIdtune()),
                                "sharetune" => UrlRewriting::generateURL("Share",$tune->getIdtune()),
				"deleteadd" => $tuneaction['deleteadd']);
			$i++;
		}

		$data['suggestedfriends'] = FriendsController::suggestedFriends(3);
		
		$this->render("SongslistView.html.twig",$data);
	}

}

?>