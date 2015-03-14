<?php

/***************************************************************/
/*                    Class MultiValuesField                   */
/*   specific for field type: select, checkbox, radio button   */
/***************************************************************/
class MultiValuesField extends Field
{
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, array $value) {
		PARENT::__construct($label,$name,$type,$attr,$value);
	}

	//SPECIFIC METHODS
	public function display() {
		return 1;
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