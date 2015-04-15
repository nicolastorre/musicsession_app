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
        
        public static function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
        {
            // Date et heure de l'erreur
            $dt = date("Y-m-d H:i:s (T)");

            // Définit un tableau associatif avec les chaînes d'erreur
            // En fait, les seuls niveaux qui nous interessent
            // sont E_WARNING, E_NOTICE, E_USER_ERROR,
            // E_USER_WARNING et E_USER_NOTICE
            $errortype = array (
                        E_ERROR              => 'Erreur',
                        E_WARNING            => 'Alerte',
                        E_PARSE              => 'Erreur d\'analyse',
                        E_NOTICE             => 'Note',
                        E_CORE_ERROR         => 'Core Error',
                        E_CORE_WARNING       => 'Core Warning',
                        E_COMPILE_ERROR      => 'Compile Error',
                        E_COMPILE_WARNING    => 'Compile Warning',
                        E_USER_ERROR         => 'Erreur spécifique',
                        E_USER_WARNING       => 'Alerte spécifique',
                        E_USER_NOTICE        => 'Note spécifique',
                        E_STRICT             => 'Runtime Notice',
                        E_RECOVERABLE_ERROR => 'Catchable Fatal Error'
                        );
            // Les niveaux qui seront enregistrés
            $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

            $err = "<errorentry>\n";
            $err .= "\t<datetime>" . $dt . "</datetime>\n";
            $err .= "\t<errornum>" . $errno . "</errornum>\n";
            $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
            $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
            $err .= "\t<scriptname>" . $filename . "</scriptname>\n";
            $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";

            if (in_array($errno, $user_errors)) {
                $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
            }
            $err .= "</errorentry>\n\n";

            // sauvegarde de l'erreur, et mail si c'est critique
            error_log($err, 3, "error.xml");
            //echo "<script>window.location='ErrorController.php'</script>";
            //  create ErrorController
            if ($errno == E_USER_ERROR) {
                // mail("nico@gmail.com","Critical User Error",$err);
            }
        }
	
	public function indexAction() {
		// get request
		$request = new Request();
                unset($_SESSION['iduser']);
                unset($_SESSION['pseudo']);
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