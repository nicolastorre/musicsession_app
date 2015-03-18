<?php

/******************************************************************************/
// Class UrlRewriting to filter and generate URL by hiding real parameters
// $url => array to contain array of name url, ...
/******************************************************************************/
class UrlRewriting
{
	private static $url = array();
	const src = "Ressources/public/images/";

	public static function addURL($urlname,$controller,$action) {
		self::$url[$urlname] = array("controller" => $controller, "action" => $action);
	}

	public static function generateURL($urlname,$par = "") {
		return self::$url[$urlname]["controller"]."/".self::$url[$urlname]["action"]."/".$par;
	}

	public static function generateSrcUser($userfolder, $file) {
		return self::src.$userfolder."/".$file;
	}

}

?>