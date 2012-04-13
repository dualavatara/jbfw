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

	public function thumbnailize($filename, $width, $height) {
		$image = new \Imagick($filename);
		$w = $image->getimagewidth();
		$h = $image->getimageheight();

		//recal empty params
		$width = !$width ? (floatval($w)/$h) * $height : $width; //if $with=0 then use image width
		$height = !$height ? (floatval($h)/$w) * $width : $height; //if $height=0 then use image height

		//normalize params
		$width = $width > $w ? $w : $width;
		$height = $height > $h ? $h : $height;
		//calculate crop size

		$aw = $w / floatval($width);//во сколько раз запрошенное меньше ширины
		$ah = $h / floatval($height);//во сколько раз запрошенное меньше высоты
		if ($aw < $ah){
			//$image->chopimage($w, $h*$aw, 0, 0);
			$nw = $w;
			$nh = ($h / $ah)*$aw;
		}
		else {
			//$image->chopimage($w * $ah, $h, 0, 0);
			$nw =($w / $aw) * $ah;
			$nh = $h;
		}
		$image->cropImage($nw, $nh, 0, 0);
		$image->thumbnailImage($width, $height);
		$image->setImageFormat('png');
		return $image->getImageBlob();
	}
	
	public function storeImageThumbnail($image_name, $width, $height) {
		$image = $_FILES[$image_name];

		if (!is_array($image['error'])) { // One image uploaded
			if ($image && $image['error'] === 0) {
				if ($data = $this->thumbnailize($image['tmp_name'], $width, $height)) {
					$key = $this->getImageKey($image['name'],'thumbnail' . $width . 'x' . $height, '.png');
					$this->write($key, $data);
					return $key;
				}
			}
		} else {                         // Array of images uploaded
			$result = array();

			for ($i = 0; $i < count($image['error']); $i++) {
				if (0 === $image['error'][$i]) {
					if ($data = $this->thumbnailize($image['tmp_name'], $width, $height)) {
						$key = $this->getImageKey($image['name'][$i],'thumbnail' . $width . 'x' . $height, '.png');
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
	private function getImageKey($imageName, $addition = '', $extension = false) {
		// Get extensions from filename - part from the last dot to the end
		$match = array();
		if (!$extension && preg_match('/\.([^\.]*)$/', $imageName, $match)) {
			$extension = $match[0];
		}
		
		$hash = md5($imageName . $addition . rand());
		$key = 'data/' . $hash . $extension;
		
		return $key;
	}
}