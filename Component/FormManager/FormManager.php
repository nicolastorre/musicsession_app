<?php

/******************************************************************************/
// Class FormManager to create, display, validate and return values of a form and add fields
// $parametres => array
/******************************************************************************/
class FormManager
{
	private $idform; //specific form ID
	private $action;
	private $method;
	private $name;
	private $class;
	private $fieldlist;  //type array: contain all input objects

	//CONSTRUCTOR
	public function __construct($name,$idform,$action = null,$method="POST",$style="") {
		$this->idform = $idform;
		$this->action = $action;
		$this->name = $name;
		$this->method = $method;
		$this->style = $style;
	}

	public function __destruct() {
		$_SESSION[$this->idform] = serialize($this);
	}

	public function addField($label,$name,$type, $values, array $attr = array(), $raw = false) {
		switch ($type) { //according to the type of the input
			case 'radio':
			case 'checkbox':
				//create CheckBoxRadioField object
				$this->fieldlist[] = new CheckBoxRadioField($label,$name,$type,$attr,$values,$raw);
				break;
			case 'select':
				//create SelectField object
				$this->fieldlist[] = new SelectField($label,$name,$type,$attr,$values,$raw);
				break;
			case 'textarea':
				//create TextareaField object
				$this->fieldlist[] = new TextareaField($label,$name,$type,$attr,$values,$raw);
				break;
			case 'email':
				//create EmailField object
				$this->fieldlist[] = new EmailField($label,$name,$type,$attr,$values,$raw);
				break;
			case 'file':
				$type = "file";
				//create EmailField object
				$this->fieldlist[] = new ImageField($label,$name,$type,$attr,$values,$raw);
				break;
			case 'submit':
				//create SubmitField object
				$this->fieldlist[] = new SubmitField($label,$name,$type,$attr,$values,$raw);
				break;
			default:
				//create InputField object
				$this->fieldlist[] = new InputField($label,$name,$type,$attr,$values,$raw);
				break;
		}
	}

	public function createView() {
		$form = array();
		if ($this->action != NULL) {
			$form["formstart"] =  "<form action='$this->action' name='$this->name' method='$this->method' $this->style enctype='multipart/form-data'>"; //form tag with action attribute
		} else {
			$form["formstart"] =  "<form name='$this->name' method='$this->method' $this->style enctype='multipart/form-data'>"; //form tag without action attribute
		}
		foreach ($this->fieldlist as $i) {
			$form[$i->getName()] = $i->display();
		}
		$form["formend"] = "</form>";
		return $form;
	}

	public function validate(Request &$request) {
		$out = true;
		foreach ($this->fieldlist as $field) {
			if (!$field->validate($request)) {
				$out = false;
			}
		}
		return $out;
	}

	public function getValues() {
		$data = array();
		foreach ($this->fieldlist as $field) {
			$data[$field->getName()] = $field->getValue();
		}
		return $data;
	}

}


?>