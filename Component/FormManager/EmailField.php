<?php

/*********************************************************************/
/*                         Class inputField                          */
/*           specific for field type: text, email, number, ...       */
/*********************************************************************/
class EmailField extends Field 
{

	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value, $error, $raw) {
		PARENT::__construct($label,$name,$type,$attr,$value,$error,$raw);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		$attrview = "id='".$this->name."'";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->errormsg</div><label for=\"$this->name\">$this->label</label><input name='$this->name' type='$this->type' $attrview value='".$this->value."'>";
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
				return true;
			} else {
                                $this->errormsg = $this->error;
				return false;
			}
		} else {
                        $this->errormsg = $this->error;
			return false;
		}
	}

}

?>