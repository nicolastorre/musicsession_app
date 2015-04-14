<?php

/*********************************************************************/
/*                         Class inputField                          */
/*           specific for field type: text, email, number, ...       */
/*********************************************************************/
class InputField extends Field 
{

	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value, $error, $raw) {
		PARENT::__construct($label,$name,$type,$attr,$value, $error, $raw);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->errormsg</div><label for=\"$this->name\">$this->label</label><input name='$this->name' type='$this->type' $attrview value='".$this->value."'>";
	}

}

?>