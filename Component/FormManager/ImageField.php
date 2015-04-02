<?php

class ImageField extends Field
{
	private $target_dir;
	//CONSTRUCTOR
	public function __construct($label,$name,$type,array $attr, $value) {
                $this->target_dir = UrlRewriting::generateSRC("tmp","","");
		$this->label = $label;
		$this->name = $name;
		$this->type = $type;
		$this->attr = $attr;
		$this->value = $value;
		$this->error = "";
	}

	public function display() {
		$attrview = "";
		foreach ($this->attr as $key => $value) {
			$attrview .= $key."=".$value." ";
		}
		return "\n\t<div class='error'>$this->error</div><label for=\"$this->name\">$this->label</label><input name='$this->name' type='$this->type' $attrview>";
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
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != "pdf" ) {
		    return false;
		} else {
			return true;
		}
	}

	public function validate(Request &$request) {
		if ($request->existsParameter($this->getName())) {
			$value = $request->getParameter($this->getName());
			$this->setValue($value);

                        if ($this->notEmpty() && $this->notOverflow(500000) && $this->notFakeFile() && $this->checkFormatAllowed()) {
				$this->error = "";
				$target_file = $this->target_dir . basename($this->value["name"]);
				 if (move_uploaded_file($this->value["tmp_name"], $target_file)) {
				 	$this->error = "";
			  	} else {
			        $this->error = "Sorry, there was an error uploading your file.";
			        return false;
			    }
				return true;
			} else {
				$this->error = "Sorry, there was an error uploading your file.2";
				return false;
			}
		} else {
			$this->error = "error";
			return false;
		}
	}


}

?>