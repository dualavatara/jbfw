<?
require_once('config/config.php');
require_once('lib/cache.mb.lib.php');
require_once('lib/cache.file.lib.php');

interface ICache {
	public function get($key);
	public function lock($key);	
	public function unlock($key);
	public function set($key, $value, $ttl, $unlock);
	public function delete($key);
	public function isExpired($key);
	public function flush();
}

?>