<?php

/**
 * DefaultController
 *
 * DefaultController is used for the common element of the web page as menu elements
 * The BaseController parent manages the creation of the view
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class DefaultController extends baseController
{
	/**
	 * Summary.
	 *
	 * @access private
	 * @static
	 * @var array $menuitem contains the key name of each menu elements.
	 */
	private static $menuitem = array("Home","Notifications","Messages","NewSong","Parameters","logout");

	/**
    * generate the general menu item from self::$menuitem containing the key name of each menu elements
    *
    * @param String $pseudo pseudo of the concerning user.
    * @return array $data each elements contain the data of its corresponding module
    * and ths required array item for the twig template
    */
	public static function initModule($pseudo) {
		$data = array();
		$data['profilcard'] = ProfilController::ProfilCard($pseudo); // init the Profile Card module
		$data['tunelistwidget'] = SongslistController::songlistwidgetAction();
		$data['suggestedfriends'] = FriendsController::suggestedFriends(3); // init the Suggested Friends module
		$data['terms'] = array('url' => UrlRewriting::generateURL("Terms",""), 'name' => Translator::translate("Terms"));
		$data['privacy'] = array('url' => UrlRewriting::generateURL("Privacy",""), 'name' => Translator::translate("Privacy"));
		$data['accessibility'] = array('url' => UrlRewriting::generateURL("Accessibility",""), 'name' => Translator::translate("Accessibility"));
		return $data;
	}
	
	/**
    * generate the general menu item from self::$menuitem containing the key name of each menu elements
    *
    * @return array $menulist foreach menu elements contain an array with the translated name of the menu item
    * and its url.
    */
	public static function menuAction() {
		$request = new Request();
		if ($request->existsParameter('controller') && ($request->getParameter('controller') != '')) {
			$ctrler = $request->getParameter('controller');
		} else {
			$ctrler = "Home";
		}
		if (isset($_SESSION['access']) and $_SESSION['access']) {
			self::$menuitem[3] = "Backoffice";
		}
		$menulist = array();
		$k = 1;
		foreach (self::$menuitem as $i) {
			if ($ctrler == $i) {
				$class = "on";
			} else {
				$class = "off";
			}
			$menulist[] = array("id" => "menu-".$i, "index" => $k,"name" => Translator::translate($i), "url" => UrlRewriting::generateURL($i,""), "class" => $class, "icon" => UrlRewriting::generateSRC("imgapp","",$i).".png");
			$k++;
		}
        $f = new FormManager("searchform","searchform",UrlRewriting::generateURL("Search",""));
        $f->addField("","inputsearch","text","","Error");
        $f->addField("Submit ","submitsearch","submit","?",Translator::translate("Invalid"));
        $menulist['searchform'] = $f->createView();
		return $menulist;
	}
}

?>