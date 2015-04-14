<?php

/********************************************************/
/*                   Class submitField                  */
/*         specific for field type: submit button       */
/********************************************************/
class SubmitField extends Field 
{
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value,$error,$raw) {
		PARENT::__construct($label,$name,$type,$attr,$value,$error,$raw);
	}
	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<input name='$this->name' type='submit' $attrview value='".$this->value."'>";
	}

	public function validate(Request &$request) {
		return true;
	}
}

?>