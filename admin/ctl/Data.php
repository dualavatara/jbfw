<?php

class Data extends Admin\Controller {
	
	public function do_get(\Admin\Request $request) {
		try {
			$key = $request['key'];

			$storage = new ImageStorage($this->app->getConfig()->dataPath);

			// Get extensions from key - part from the last dot to the end
			$match = array();
			$extension = '';
			if (preg_match('/\.([^\.]*)$/', $key, $match)) { 
				$extension = $match[1];
			}
			
			$contentType = $storage->getContentType($extension);
			$data = $storage->read($key);

			return new Admin\Response($data, 200, array(
				'Content-Type' => $contentType
			));
		} catch (Exception $e) {
			return $this->app->error404();
		}
	}
}