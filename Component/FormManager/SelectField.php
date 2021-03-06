<?php

/********************************************************/
/*                  Class selectField                   */
/*           specific for field type: select            */
/********************************************************/
class SelectField extends MultiValuesField
{
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, array $value, $error,$raw) {
		PARENT::__construct($label,$name,$type,$attr,$value,$error,$raw);
	}

	//SPECIFIC METHODS

	public function display() { //display the field in HTML
		$attrview = "";
		$attrview = "id='".$this->name."'";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		$options = "";
		foreach ($this->value as $v) {
			if ($v['s']) {
				$v['s'] = "selected";
			} else {
				$v['s'] = "";
			}
			$options .= "<option value=".$v['v']." ".$v['s'].">".$v['v']."</option>";
		}
		return "\n\t<div class='error'>$this->errormsg</div><label for=\"$this->name\">$this->label</label><select name='$this->name' $attrview >$options</select>";
	}

	public function getValue() {
		$values = array();
		foreach ($this->value as $v) {
			if ($v['s']) {
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
				$this->value[$i]['s'] = true;
			} else {
				$this->value[$i]['s'] = false;
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
				return true;
			} else {
                                $this->errormsg = $this->error;
				return false;
			}
		} else {
			$value = array();
			$this->setValue($value);
                        $this->errormsg = $this->error;
			return false;
		}
	}
}

?>