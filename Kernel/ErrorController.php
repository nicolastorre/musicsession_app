<?php

/******************************************************************************/
// Class ErrorController call when catching an error
//$errormsg => string specifying the error
/******************************************************************************/
class ErrorController extends BaseController
{
	private $errormsg;

	public function __construct($errormsg="")	 {
		$this->errormsg = $errormsg;
	}
	
	public function indexAction() {
		// get request
		$request = new Request();
		if ($request->existsParameter('controller') && $request->notEmptyParameter('controller')) {
    		$controller = $request->getParameter('controller')."Controller";
 		} else {
 			$controller = "";
 		}
 		if ($request->existsParameter('action') && $request->notEmptyParameter('action')) {
			$action = $request->getParameter('action')."Action";
		} else {
			$action = "";
		}
		if ($request->existsParameter('par') && $request->notEmptyParameter('par')) {
			$par = $request->getParameter('par');
			$para = "";
			foreach ($par as $v) {
				$para .= $v."/";
			}
		} else {
			$para = "";
		}

		$this->errormsg = "URL: ".$controller."/".$action."/".$para."\tError: ".$this->errormsg;

		$lw = new LogWriter();
        $lw->writeLog($this->errormsg);

		$data = array("error" => "");

		$this->render("ErrorView.html.twig",$data);
	}
}