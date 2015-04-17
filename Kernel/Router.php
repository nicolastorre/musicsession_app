<?php 

/******************************************************************************/
// Class Router routing the query to the right Controller
/******************************************************************************/
class Router 
{

  // Routing of the query
  public function queryRouter() {
    try {

      // configuration of every routes with their parameters
      Routing::routingList();
      Routing::srcList();

      // get request
      $request = new Request();
      $request->getSession();
      
      // error_reporting(0);
      // $error_handler = set_error_handler("ErrorController::userErrorHandler");

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

  // create the Controller name from the url parameters
  public function getControllerName(Request $request) {
    $controller = "Home";
    if ($request->existsParameter('controller') && $request->notEmptyParameter('controller')) {
      $controller = $request->getParameter('controller');
      $controller = ucfirst(strtolower($controller));
    }
    return $controller."Controller";
  }

  // create the action name from the url parameters
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