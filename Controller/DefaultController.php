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
	private static $menuitem = array("Home","Notifications","Messages","NewSong","Parameters");
	
	/**
     * generate the general menu item from self::$menuitem containing the key name of each menu elements
     *
     * @return array $menulist foreach menu elements contain an array with the translated name of the menu item
     * and its url.
     */
	public static function menuAction() {
		$menulist = array();
		foreach (self::$menuitem as $i) {
			$menulist[] = array("name" => Translator::translate($i), "url" => UrlRewriting::generateURL($i,""));
		}
		return $menulist;
	}
}

?>