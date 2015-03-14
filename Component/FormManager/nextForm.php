<?php

/********************************************************/
/*                  Class textareaField                 */
/*           specific for field type: textarea          */
/********************************************************/
class TextareaField extends Field {
	//DISPLAY OBJECT
	public function __toString() {
		return "textarea du idForm= $this->_idForm name='$this->_name' placeholder='$this->_placeholder' label='$this->_label'
		style='$this->_style'";
	}
	//CONSTRUCTOR
	public function __construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm) {
		PARENT::__construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm);
	}
	//SPECIFIC METHODS
	public function display() { //display the field in HTML
		echo "\n\t<div><span>$this->_label</span><textarea name='$this->_name' placeholder='$this->_placeholder' $this->_style></textarea></div>";
	}
}

/********************************************************/
/*                   Class submitField                  */
/*         specific for field type: submit button       */
/********************************************************/
class SubmitField extends Field {
	//DISPLAY OBJECT
	public function __toString() {
		return "submit du idForm= $this->_idForm name='$this->_name' type='$this->_type' placeholder='$this->_placeholder' label='$this->_label'
		style='$this->_style'";
	}
	//CONSTRUCTOR
	public function __construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm) {
		PARENT::__construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm);
	}
	//SPECIFIC METHODS
	public function display() { //display the field in HTML
		echo "\n\t<div><span>$this->_label</span><input name='$this->_name' type='$this->_type' $this->_style></div>";
	}
}

/***********************************************************/
/*                 Class multiValuesField                  */
/*      specific for field displaying multiple choice      */
/***********************************************************/
abstract class MultiValuesField extends Field {
	protected $_multiValues; // type array: contain all possible values of the field

	//DISPLAY OBJECT
	public function __toString() {
		return "input du idForm= $this->_idForm name='$this->_name' type='$this->_type' placeholder='$this->_placeholder' label='$this->_label'
		style='$this->_style' values='".implode(",",$_multiValues);
	}
	//CONSTRUCTOR
	public function __construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm) {
		PARENT::__construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm);
		$mv = new MultiValuesManager();
		$valuesField = $mv->queryValuesField($this->_idChamps);
		foreach ($valuesField as $i) {
			$this->_multiValues[] = $i['valeur'];
		}
	}
	//SPECIFIC METHODS
	public function display() {} //display the field in HTML
}

/********************************************************/
/*               Class checkBoxRadioField               */
/*   specific for field type: checkbox and radiobutton  */
/********************************************************/
class CheckBoxRadioField extends multiValuesField {
	//CONSTRUCTOR
	public function __construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm) {
		PARENT::__construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm);
	}
	//SPECIFIC METHODS
	public function display() { //display the field in HTML
		echo "<div><span>$this->_label</span><span>$this->_placeholder</span>";
		foreach ($this->_multiValues as $i) {
			echo "\n\t<div><input name='$this->_name' type='$this->_type' $this->_style value='$i'><span>$i</span></div>";
		}
		echo "</div>";
	}
}

/********************************************************/
/*                  Class selectField                   */
/*           specific for field type: select            */
/********************************************************/
class SelectField extends multiValuesField {
	//CONSTRUCTOR
	public function __construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm) {
		PARENT::__construct($idChamps,$name,$type,$placeholder,$label,$style,$idForm);
	}
	//SPECIFIC METHODS
	public function display() { //display the field in HTML
		echo "\n\t<div><span>$this->_label</span><span>$this->_placeholder</span><select name='$this->_name' $this->_style>";
		foreach ($this->_multiValues as $i) {
			echo "<option value='$i'>$i</option>";
		}
		echo "</select></div>";
	}
}

?>