<?php

/******************************************************************************/
// Class Request to handle the $_GET and $_POST in the same array (class to improve!)
// $parameters => array
/******************************************************************************/
class Request {

  // paramètres de la requête
  private $parameters;

  public function __construct() {
    $this->parameters = array_merge($_GET, $_POST, $_FILES);
    array_walk_recursive( $this->parameters, array($this,"clean"));
  }

  // Renvoie vrai si le paramètre existe dans la requête
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

  public function getSession() {
    session_start();
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