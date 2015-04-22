<?php

class Translator
{
	
	public static function translate($key_dico) {
        $key_dico = htmlspecialchars($key_dico, ENT_QUOTES);
		$lang = $_SESSION['lang'];
		$db = new DBManager();
		$tsl = $db->query("SELECT * from dico WHERE key_dico = (?);",array($key_dico));
		if (!empty($tsl)) {
			return htmlspecialchars($tsl[0][$lang], ENT_QUOTES);
		} else {
			return $key_dico;
		}
	}
}

?>