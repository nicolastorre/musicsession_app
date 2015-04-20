<?php
/**
* File containing the FooterController Class
*
*/

/**
* FooterController
*
* FooterController class refirect to the display of terms, privacy and accessibility pages in the current language
* The BaseController parent manages the creation of the view
*
*
* @package    MusicSessionApp
* @author     Nicolas Torre <nico.torre.06@gmail.com>
*/
class FooterController extends BaseController
{

	/**
	* Display the accessibility page
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function accessibilityAction(Request &$request) {
		$data = DefaultController::initModule($_SESSION['pseudo']);

		if($_SESSION['lang'] == "fr") {
			$this->render("Accessibility_frView.html.twig",$data);
		} else {
			$this->render("Accessibility_enView.html.twig",$data);
		}
	}

	/**
	* Display the privacy page
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function privacyAction(Request &$request) {
		$data = DefaultController::initModule($_SESSION['pseudo']);

		if($_SESSION['lang'] == "fr") {
			$this->render("Privacy_frView.html.twig",$data);
		} else {
			$this->render("Privacy_enView.html.twig",$data);
		}
	}

	/**
	* Display the terms page
	*
	* @param Request &$request Request object.
	* @return void .
	*/
	public function termsAction(Request &$request) {
		$data = DefaultController::initModule($_SESSION['pseudo']);

		if($_SESSION['lang'] == "fr") {
			$this->render("Terms_frView.html.twig",$data);
		} else {
			$this->render("Terms_enView.html.twig",$data);
		}
	}
}

?>