<?php

class FileField extends Field
{
	private $target_dir;
	private $extension;

	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value, $error, $raw,$ext) {
                PARENT::__construct($label,$name,$type,$attr,$value,$error,$raw);
                $this->target_dir = UrlRewriting::generateSRC("tmp","","");
                $this->extension = $ext;
	}

	public function display() {
		$attrview = "";
		$attrview = "id='".$this->name."'";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->errormsg</div><label for=\"$this->name\">$this->label</label><div class='upload'><input name='$this->name' type='$this->type' $attrview></div>";
	}

	public function notFakeFile() {
		// Check if image file is a actual image or fake image
	    $check = filesize($this->value["tmp_name"]);
	    if($check !== false) {
	        return true;
	    } else {
	    	return false;
	    }
	}

	public function notEmpty() {
		if (!empty($this->value['tmp_name'])){
			return true;
		} else {
			return false;
		}
	}

	public function notOverflow($size) {
		// Check file size
		if ($this->value["size"] < $size) {
		    return true;
		} else {
			return false;
		}
	}

	public function checkFormatAllowed() {
		// Allow certain file formats
		$imageFileType = pathinfo(basename($this->value["name"]),PATHINFO_EXTENSION);
		if((($this->extension == "") && ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"
		|| $imageFileType == "gif")) || ($this->extension == "pdf" && $imageFileType == "pdf" )) {
		    return true;
		} else {
			return false;
		}
	}

	public function validate(Request &$request) {
		if ($request->existsParameter($this->getName())) {
			$value = $request->getParameter($this->getName());
			$this->setValue($value);

            if ($this->notEmpty() && $this->notOverflow(700000) && $this->notFakeFile() && $this->checkFormatAllowed()) {
				$this->error = "";
				$target_file = $this->target_dir . basename($this->value["name"]);
				 if (move_uploaded_file($this->value["tmp_name"], $target_file)) {
			  	} else {
                                $this->errormsg = $this->error;
			        return false;
			    }
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