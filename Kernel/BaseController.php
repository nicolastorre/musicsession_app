<?php

require_once('Component/Twig/lib/Twig/Autoloader.php');

/******************************************************************************/
// Class BaseController to generate the view by using the right template
/******************************************************************************/
class BaseController
{
	
	public function render($template,$data) {
		if (file_exists("Views/".$template)) {
			$webroot = ConfigReader::get("webroot", "/");
			extract($data);

			//array_walk_recursive($data, array($this,"clean")); // cleaning special char but don't use with twig

	    	Twig_Autoloader::register();
	    	$loader = new Twig_Loader_Filesystem('Views'); // Dossier contenant les templates
			$twig = new Twig_Environment($loader, array('debug' => true, 'cache' => false));
			$twig->addExtension(new Twig_Extension_Debug());

			// $twig->addFilter('menuAction', new Twig_Filter_Function('DefaultController::menuAction'));
			$twig->addFunction('menuAction', new Twig_Function_Function('DefaultController::menuAction'));

			ob_start();
				echo $twig->render($template, array("webroot" => $webroot,
						"data" => $data));
			ob_get_flush();
		}
		else {
                        throw new Exception("Not found $template template");
		}
	}

	private function clean(&$value) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
  	}
}

?>