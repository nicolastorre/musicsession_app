<?php
/**
* File containing the Request Class
*
*/

/**
* Request
*
* Request class is used to manage the GET, POST FILES variables and initialize session
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class Request {

  /**
  * @var Array $parameters array which contains GET, POST and FILES variables
  */
  private $parameters;

  public function __construct() {
    $this->parameters = array_merge($_GET, $_POST, $_FILES);
    array_walk_recursive( $this->parameters, array($this,"clean"));
  }

  /**
  * Test if the parameter exist
  *
  * @param String $nom nom is the name of the testing parameters.
  * @return boolean.
  */
  public function existsParameter($nom) {
    return (isset($this->parameters[$nom])); // && $this->parameters[$nom] != "");
  }

  // Renvoie vrai si le paramètre n'est pas vide dans la requête
  public function notEmptyParameter($nom) {
    return (!empty($this->parameters[$nom]));
  }

  // Renvoie la valeur du paramètre demandé
  // Lève une exception si le paramètre est introuvable
  public function getParameter($nom) {
    if ($this->existsParameter($nom)) {
      return $this->parameters[$nom];
    }
    else {
      $error = "Not found parameter '$nom' in the request";
      $e = new ErrorController($error);
      $e->indexAction();
      exit(1);
    }
  }

  public function setParameter($key, $value) {
    $this->parameters[$key] = $value;
  }

  public function getSession() {
    if(!isset($_SESSION))
    {
      session_start();
    }
  }

  public function isGET() {
    return isset($_GET);
  }

  public function isPOST() {
    return isset($_POST);
  }

  private function clean(&$value) {
      if (!is_numeric($value)) {
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
      }
    }
}
