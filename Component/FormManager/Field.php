<?php

/*******************************************/
/*           Abstract Class Field          */
/*          Common for each field          */
/*******************************************/
abstract class Field {
	protected $label;
	protected $name;
	protected $type;
	protected $attr;
	protected $value;
	protected $error;
	protected $raw;
        protected $errormsg;

	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value, $error, $raw) {
		$this->label = $label;
		$this->name = $name;
		$this->type = $type;
		$this->attr = $attr;
		$this->value = $value;
		$this->error = $error;
		$this->raw = $raw;
                $this->errormsg = "";
	}
	//SPECIFIC METHODS
	abstract public function display(); //display the field in HTML

	public function notEmpty() {
		return (!empty($this->value));
	}

	public function notOverflow($maxsize) {
		if (strlen($this->value) <= $maxsize) {
			return true;
		} else {
			return false;
		}
	}

	public function validate(Request &$request) {
		if (!$this->raw) {
			if ($request->existsParameter($this->getName())) {
				$value = $request->getParameter($this->getName());
				$this->setValue($value);
				if ($this->notEmpty() && $this->notOverflow(255)) {
					return true;
				} else {
                                        $this->errormsg = $this->error;
					return false;
				}
			} else {
                                $this->errormsg = $this->error;
				return false;
			}
		} else {
			return true;
		}
	}

	//GETTERS
	public function get($attr) { //general getter
		return $this->$attr;
	}

	public function getName() {
		return str_replace("[]", "", $this->name);
	}

	//SETTERS
	public function set($attr,$val) { //general setter
		$this->$attr = $val;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

}

?>
