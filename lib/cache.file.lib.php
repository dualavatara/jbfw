<?
require_once('config/config.php');
require_once('lib/cache.lib.php');

class FileCache implements ICache {
	const ttlMult = 2;
	private static $lockHash = array();
	
	private $dir = NULL;
	
	public function __construct($dir = FILECACHE_DIR) {
		$this->dir = $dir;
		if (!is_dir($this->dir)) {
			if (!@mkdir($this->dir)) throw new Exception("Can't create $this->dir");
			@chmod($this->dir, 0775);
		}
	}
	
	private function _add($key, $value) {
		$key = md5($key);
		$fname = $this->dir . '/' . $key;
		if (is_file($fname)) return false;
		return !(file_put_contents($fname, serialize($value)) === false);
	}
	
	private function _set($key, $value) {
		$key = md5($key);
		$fname = $this->dir . '/' . $key;
		return !(file_put_contents($fname, serialize($value)) === false);
	}
	
	private function _delete($key) {
		$key = md5($key);
		$fname = $this->dir . '/' . $key;
		return unlink($fname);
	}
	
	public function get($key) { //check if unserialize required on returned object
		$key = md5($key);
		$fname = $this->dir . '/' . $key;
		if (!is_file($fname)) return NULL;
		$content = file_get_contents($fname);
		if (!$content) return NULL;
		return unserialize($content);
	}
	
	public function lock($key) {
		if (self::$lockHash[$key]) return true; //process already locked $key record
		
		if ($this->_add($key.'mutex', true)) { //lock mutex for key
			self::$lockHash[$key] = true;
		} else return false;
		
		return true;
	}
	
	public function unlock($key) {
		if (self::$lockHash[$key]) { //$key record locked by current process
			self::$lockHash[$key] = NULL;
			$this->_delete($key.'mutex');
			
		} else return false;
		return true;
	}
	
	public function set($key, $value, $ttl, $unlock = true) {
		try {
			if (is_array($key)) throw new Exception('Can not set() array of keys.');

			if ($this->lock($key)) { //lock mutex for key
				$res = $this->_set($key, $value, 0, $ttl * self::ttlMult);

				//set associated array of logical cache actuality
				$this->_set($key.'ttl', (time() + $ttl), 0, $ttl * self::ttlMult);
				
				if ($unlock) $this->unlock($key);
			} else throw new Exception("Set failed. Object key($key) is locked.");

		} catch (Exception $e) {
			Logger::obj()->error($e->getMessage());
			return false;
		}
		return true;
	}
	
	public function delete($key){
		try {
			if ($this->_add($key.'mutex', true)) { //mutex for key
				$this->_delete($key);
				$this->_delete($key.'ttl');
				$this->_delete($key.'mutex');
			} else throw new Exception("Delete failed. Object key($key) is locked.");
		} catch (Exception $e) {
			Logger::obj()->error($e->getMessage());
			return false;
		}
		return true;
	}

	public function isExpired($key) {
		$ttl = $this->get($key.'ttl');
		return ($ttl <= time());
	}

	public function flush() {
		$files = glob($this->dir. "/*");
		foreach($files as $file) unlink($file); 
	}
}
?>