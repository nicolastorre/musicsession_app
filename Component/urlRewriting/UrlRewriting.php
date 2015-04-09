<?php

/******************************************************************************/
// Class UrlRewriting to filter and generate URL by hiding real parameters
// $url => array to contain array of name url, ...
/******************************************************************************/
class UrlRewriting
{
	private static $url = array();
        private static $src = array();
        
//	const srcUser = "Ressources/public/data/Users/";
//	const srcApp = "Ressources/public/images/app/";
//	const srcPDFScores = "Ressources/public/PDFScores/";

	public static function addURL($urlname,$controller,$action) {
		self::$url[$urlname] = array("controller" => $controller, "action" => $action);
	}
        
        public static function addSRC($srcname, $path) {
		self::$src[$srcname] = $path;
	}

	public static function generateURL($urlname,$par = "") {
		return self::$url[$urlname]["controller"]."/".self::$url[$urlname]["action"]."/".$par;
	}
        
        public static function generateSRC($srcname, $folder, $file, $default = "") {
            if (($folder != "") && (file_exists(self::$src[$srcname].$folder."/".$file)  || $default == "")) {
		return self::$src[$srcname].$folder."/".$file;
            } 
            elseif (file_exists(self::$src[$srcname].$file) || $default == "") {
                return self::$src[$srcname].$file;
            } else {
                return self::$src[$srcname].$default;
            }
	}

}

?>