<?php

/********************************************************/
/*               Class checkBoxRadioField               */
/*   specific for field type: checkbox and radiobutton  */
/********************************************************/
class CheckBoxRadioField extends MultiValuesField 
{
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, array $value,$raw) {
		PARENT::__construct($label,$name,$type,$attr,$value,$raw);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		$output = array();
		$output["header"] = "\n\t<div class='error'>$this->error</div><legend>$this->label</legend>";
		foreach ($this->value as $v) {
			if ($v['c']) {
				$v['c'] = "checked";
			} else {
				$v['c'] = "";
			}
			$output[] = "<input name='$this->name' type='$this->type' ".$attrview." value='".$v['v']."' ".$v['c']."><label for=\"$this->name\">".$v['v']."</label>";
		}
		return $output;
	}

	public function getValue() {
		$values = array();
		foreach ($this->value as $v) {
			if ($v['c']) {
				$values[] = $v['v'];
			}
		}
		return $values;
	}

	public function setValue($value) {
		if(!is_array($value)) {
			$value = array($value);
		}
		for($i=0;$i<count($this->value);$i++) {
			if (in_array($this->value[$i]['v'],$value)) {
				$this->value[$i]['c'] = true;
			} else {
				$this->value[$i]['c'] = false;
			}
		}
	}

	public function notEmpty() {
		foreach ($this->value as $i) {
			if (empty($i['v'])) {
				return false;
			}
		}
		return true;
	}

	public function notOverflow($maxsize) {
		foreach ($this->value as $i) {
			if (strlen($i['v']) > $maxsize) {
				return false;
			}
		}
		return true;
	}

	public function validate(Request &$request) {
		if ($request->existsParameter(str_replace("[]", "", $this->getName()))) {
			$value = $request->getParameter(str_replace("[]", "", $this->getName()));
			$this->setValue($value);
			if ($this->notEmpty() && $this->notOverflow(255)) {
				$this->error = "";
				return true;
			} else {
				$this->error = "error";
				return false;
			}
		} else {
			$value = array();
			$this->setValue($value);
			$this->error = "error";
			return false;
		}
	}
}

?>