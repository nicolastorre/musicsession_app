<?php

/******************************************************************************/
// Class ConfigReader to read the configuration ini file
// $parametres => array
/******************************************************************************/
class ConfigReader {

  private static $parametres;

  // return the value of a parameter from the configuration file
  public static function get($nom, $valeurParDefaut = null) {
    if (isset(self::getParametres()[$nom])) {
      $valeur = self::getParametres()[$nom];
    }
    else {
      $valeur = $valeurParDefaut;
    }
    return $valeur;
  }

  // return the array of config parameters
  private static function getParametres() {
    if (self::$parametres == null) {
      $cheminFichier = "Config/prod.ini";
      if (!file_exists($cheminFichier)) {
        $cheminFichier = "Config/dev.ini";
      }
      if (!file_exists($cheminFichier)) {
        throw new Exception("Not found configuration file");
      }
      else {
        self::$parametres = parse_ini_file($cheminFichier);
      }
    }
    return self::$parametres;
  }
}
