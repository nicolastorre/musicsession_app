<?php

require_once('Gregwar/Image/autoload.php');
require_once('Gregwar/Cache/autoload.php');

use Gregwar\Image\Image;

/*
Form to upload an image
Upload an image
then rename the image
then convert format
Then crop to the right size
*/
class ImageManager
{
	const base_dir = "Ressources/public/images/";
	
	private $filepath;
	private $filename;
	private $image;

	public function __construct($filepath) {
		$this->filepath = $filepath;
		$this->filename = pathinfo($filepath, PATHINFO_BASENAME);
	}

	public function renameMove($file) {
		$source_file = $this->filepath;
		$target_file = $file;
		if (rename($source_file, $target_file)) {
			$this->filepath = $file;
			$this->filename = pathinfo($file, PATHINFO_BASENAME);
		}
	}

	public function convertInProfilePic($file) {
		$source_file = $this->filepath;
		$target_file = $file;
		if (rename($source_file, $target_file)) {
			$this->filepath = $file;
			$this->filename = pathinfo($file, PATHINFO_BASENAME);
		}
		Image::open($this->filepath)
		    ->resize(73, 73, "#346A85")
    		->save($this->filepath, 'png');
	}
}

?>