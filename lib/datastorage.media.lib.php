<?php

require_once('lib/datastorage.file.lib.php');
require_once('lib/exception.lib.php');

require_once('config/config.php');

class DataStorageMedia extends FileDataStorage implements IDataStorage{

	const BINARY_EXT = 'dat';
	const BINARY_TYPE = 'application/octet-stream';

	public $media_types = array(
		'image/gif' => 'gif',
		'image/jpg' => 'jpg',
		'image/jpeg' => 'jpg',
		'image/png' => 'png',

		'video/mpeg' => 'mpeg',
		'video/mp4' => 'mp4',

	);

	public function getContentType($ext) {
		if ($mime = array_search($ext, $this->media_types)) {
			return $mime;
		}
		
		return self::BINARY_TYPE;
	}

	public function getExtension($type) {
		if($type && isset($this->media_types[$type])) {
			return array('ext' => $this->media_types[$type], 'type' => $type);
		}
			
		return array('ext' => self::BINARY_EXT, 'type'=> self::BINARY_TYPE);
	}

}
