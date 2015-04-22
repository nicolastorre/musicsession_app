<?php 
/**
* File containing the RouterController Class
*
*/

/**
* RouterController
*
* RouterController class is used by the fronController (index.php) to redirect to the requesting Controller
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class Router 
{

  /**
  * Route the query
  *
  * Initialize the services (routing list, request object, session,), get the requesting controller and action then call them
  *
  * @return void .
  */
  public function queryRouter() {
    try {

      // configuration of every routes with their parameters
      Routing::routingList();
      Routing::srcList();

      // get request
      $request = new Request();
      $request->getSession();
      
      // error_reporting(0);
      $error_handler = set_error_handler("ErrorController::userErrorHandler");

      $controllerName = $this->getControllerName($request);
      $actionName = $this->getActionName($request);

      if (!isset($_SESSION['iduser']) && $controllerName != "AuthController") {
        $controllerName = "AuthController";
        $actionName = "indexAction";
      }

      if(class_exists($controllerName)) {
        $ctrl = new $controllerName();
        if (method_exists($ctrl, $actionName)) {
          $ctrl->$actionName($request);
        } else {
          $error = "Not exist $actionName action for $controllerName controller";
          throw new Exception($error);
        } 
      } else {
          $error = "Not exist $controllerName controller";
          throw new Exception($error);
      }
    }
    catch (Exception $e) {
      $error = $e->getMessage();
      $e = new ErrorController($error);
      $e->indexAction();
      exit(1);
    }
  }

  /**
  * Create the requesting Controller name from the request object
  *
  * @param Request &$request Request object.
  * @return void .
  */
  public function getControllerName(Request $request) {
    $controller = "Home";
    if ($request->existsParameter('controller') && $request->notEmptyParameter('controller')) {
      $controller = $request->getParameter('controller');
      $controller = ucfirst(strtolower($controller));
    }
    return $controller."Controller";
  }

  /**
  * Create the requesting Action name from the request object
  *
  * @param Request &$request Request object.
  * @return void .
  */
  public function getActionName(Request $request) {
    $action = "index";
    if ($request->existsParameter('action') && $request->notEmptyParameter('action')) {
      $action = $request->getParameter('action');
      // $action = ucfirst(strtolower($action));
      $action = strtolower($action);
    }
    return $action."Action";
  }
}

?>