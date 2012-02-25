<?
require_once('lib/cache.lib.php');

class MBCache implements ICache {
	const host = CACHE_MB_HOST;
	const port = CACHE_MB_PORT;
	const compressThreshold = CACHE_MB_THRESHOLD;
	const ttlMult = 2;
	/**
	 * @var Memcache
	 */
	private static $mcHandler = NULL;
	private static $lockHash = array();

	/**
	 * @static
	 * @return Memcache|null
	 * @throws Exception
	 */
	private static function srv() { //wrapper for self::$cache
		if (!is_object(self::$mcHandler)) {
			self::$mcHandler = new Memcache();
			if (!self::$mcHandler->pconnect(self::host, self::port)) {
				self::$mcHandler = NULL;
				throw new Exception('Connection to '.self::host.':'.self::port.' failed');
			} else self::$mcHandler->setCompressThreshold(self::compressThreshold);
		}
		return self::$mcHandler;
	}

	public function get($key) { //check if unserialize required on returned object
		try {
			return self::srv()->get($key);
		} catch (Exception $e) {
			Logger::obj()->error($e->getMessage());
			return NULL;
		}
	}
	
	public function lock($key) {
		if (self::$lockHash[$key]) return true; //process already locked $key record
		
		if (self::srv()->add($key.'mutex', true, 0)) { //lock mutex for key
			self::$lockHash[$key] = true;
		} else return false;
		
		return true;
	}
	
	public function unlock($key) {
		if (self::$lockHash[$key]) { //$key record locked by current process
			self::$lockHash[$key] = NULL;
			self::srv()->delete($key.'mutex');
		} else return false;
		return true;
	}

	public function set($key, $value, $ttl, $unlock = true) {
		try {
			if (is_array($key)) throw new Exception('Can not set() array of keys.');

			if ($this->lock($key)) { //lock mutex for key
				$res = self::srv()->set($key, $value, 0, $ttl * self::ttlMult);

				//set associated array of logical cache actuality
				self::srv()->set($key.'ttl', (time() + $ttl), 0, $ttl * self::ttlMult);
				
				if ($unlock) $this->unlock($key);
			} else throw new Exception("Set failed. Object key($key) is locked.");

		} catch (Exception $e) {
			Logger::obj()->error($e->getMessage());
			return false;
		}
		return true;
	}
	
	//atom functions 
	public function incr($key) {
		self::srv()->increment($key);
	}
	
	public function decr($key) {
		self::srv()->decrement($key);
	}
	
	public function delete($key){
		try {
			if (self::srv()->add($key.'mutex', true, 0, 0)) { //mutex for key
				self::srv()->delete($key);
				self::srv()->delete($key.'ttl');
				self::srv()->delete($key.'mutex');
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
		self::srv()->flush();
	}
}

?>