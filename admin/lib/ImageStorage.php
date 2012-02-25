<?php

require_once 'lib/datastorage.media.lib.php';

class ImageStorage extends DataStorageMedia {
	
	/**
	 * Stores uploaded image(s) (from $_FILES array) in data storage and returns key(s).
	 * You can store not only one image, but a bunch of images.
	 * In that case function return array of keys in data storage.
	 * 
	 * @param string $image_name Name of uploaded image in $_FILES
	 * 
	 * @return mixed One or more key(s) of image(s) in data storage, false if operation is not successfully.
	 */
	public function storeImage($image_name) {
		$image = $_FILES[$image_name];
		
		if (!is_array($image['error'])) { // One image uploaded
			if ($image && $image['error'] === 0) {
				if ($data = file_get_contents($image['tmp_name'])) {
					$key = $this->getImageKey($image['name']);
					$this->write($key, $data);
					return $key;
				}
			}	
		} else {                         // Array of images uploaded
			$result = array();
			
			for ($i = 0; $i < count($image['error']); $i++) {
				if (0 === $image['error'][$i]) {
					if ($data = file_get_contents($image['tmp_name'][$i])) {
						$key = $this->getImageKey($image['name'][$i]);
						$this->write($key, $data);
						
						$result[] = $key;
					}
				}
			}
			
			return $result;
		}
		
		return false;
	}

	/**
	 * Generate key for the image.
	 * Extension will be kept original to define MIME type of content.
	 * 
	 * @param string $imageName Initial name of file containing image.
	 * 
	 * @return string 
	 */
	private function getImageKey($imageName) {
		// Get extensions from filename - part from the last dot to the end
		$match = array();
		$extension = '';
		if (preg_match('/\.([^\.]*)$/', $imageName, $match)) { 
			$extension = $match[0];
		}
		
		$hash = md5($imageName . rand());
		$key = 'img/' . $hash . $extension;
		
		return $key;
	}
}