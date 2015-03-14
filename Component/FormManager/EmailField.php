<?php

/*********************************************************************/
/*                         Class inputField                          */
/*           specific for field type: text, email, number, ...       */
/*********************************************************************/
class EmailField extends Field 
{

	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value) {
		PARENT::__construct($label,$name,$type,$attr,$value);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->error</div><label for=\"$this->name\">$this->label</label><input name='$this->name' type='$this->type' $attrview value='".$this->value."'>";
	}

	public function isEmail() {
		if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function validate(Request &$request) {
		if ($request->existsParameter($this->getName())) {
			$value = $request->getParameter($this->getName());
			$this->setValue($value);
			if ($this->notEmpty() && $this->notOverflow(255) && $this->isEmail()) {
				$this->error = "";
				return true;
			} else {
				$this->error = "error";
				return false;
			}
		} else {
			$this->error = "error";
			return false;
		}
	}

}

?>