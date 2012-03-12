<?php

require_once 'lib/datastorage.lib.php';

class FileDataStorage implements IDataStorage {

	private $directory;
	
	public function __construct($directory) {
		$this->directory = realpath($directory) . '/';
	}
	
	public function write($key, $value) {
		$file = $this->getFileName($key);
		if (!file_exists(dirname($file))) {
			mkdir(dirname($file), 0766, true);
		}
		
		if (false === file_put_contents($file, $value, LOCK_EX)) {
			throw new Exception('Can not write value to the file');
		}
	}

	public function read($key) {
		$file = $this->getFileName($key);
		if (false === ($value = file_get_contents($file))) {
			throw new Exception('Can not read value from the file');	
		}
		
		return $value;
	}

	public function remove($key) {
		$file = $this->getFileName($key);
		if (false === unlink($file)) {
			throw new Exception('Can not remove file containing value');
		}

		// Recursively delete empty folders
		$dir = dirname($file);
		while(@rmdir($dir)){
			$dir = dirname($dir);
		}
	}

	private function getFileName($key) {
		return $this->directory . $key;
	}

	public function getMeta($key) {
		$file = $this->getFileName($key);

		if (false === ($modified = @filemtime($file))) {
			throw new Exception('Can not read value from the file');
		}
		
		return array(
			'modified' => $modified
		);
	}

	public function output($key) {
		$file = $this->getFileName(quoted_printable_decode($key));
		flush();
		
		if (false === readfile($file)) {
			throw new Exception('Can not read value from the file');
		}
	}

	public function getDir() {
		return $this->directory;
	}
}