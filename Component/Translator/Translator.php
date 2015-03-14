<?php

class Translator
{
	
	public static function translate($key_dico) {
		$_SESSION['lang'] = "en";
		$lang = $_SESSION['lang'];
		$db = new DBManager();
		$tsl = $db->query("SELECT * from dico WHERE key_dico = (?);",array($key_dico));
		if (!empty($tsl)) {
			return htmlspecialchars($tsl[0][$lang]);
		} else {
			return $key_dico;
		}
	}
}

?>