<?php

class Upload {

	/**
	* Stores an uploaded image in UPLOAD_DIR.
	* Only real .png, .jpg and .gif images are accepted: the file contents are checked, not just the name.
	* @param string $name, the original filename.
	* @param string $tmp_name, the temporary file PHP stored the upload in.
	* @param int $error, the upload error code.
	* @param string $prefix, optional, placed before the filename to make it unique.
	* @return mixed the filename the image was stored under, or false.
	*/
	public static function image($name,$tmp_name,$error,$prefix='') 
	{
		if($error != UPLOAD_ERR_OK || empty($name) || !is_uploaded_file($tmp_name)) {
			return false;
		}
		
		$name = preg_replace('/[^a-zA-Z0-9\.\-_ ]/','', basename($name));
		
		if(!preg_match('/^[^\.].*\.(png|jpg|gif)$/i', $name)) {
			return false;
		}
		
		$info = @getimagesize($tmp_name);
		
		if($info === false || !in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF])) {
			return false;
		}
		
		$name = $prefix . $name;
		
		if(!move_uploaded_file($tmp_name, UPLOAD_DIR . $name)) {
			return false;
		}
		
		return $name;
	}
}

?>
