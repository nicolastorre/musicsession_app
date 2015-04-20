<?php

/********************************************************/
/*                  Class textareaField                 */
/*           specific for field type: textarea          */
/********************************************************/
class TextareaField extends Field 
{
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value, $error,$raw) {
		PARENT::__construct($label,$name,$type,$attr,$value,$error,$raw);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		$attrview = "id='".$this->name."'";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->errormsg</div><label for=\"$this->name\">$this->label</label><textarea name='$this->name' $attrview>".$this->value."</textarea>";
	}

	public function validate(Request &$request) {
		if ($request->existsParameter($this->getName())) {
			$value = $request->getParameter($this->getName());
			$this->setValue($value);
			if ($this->notEmpty() && $this->notOverflow(2000)) {
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