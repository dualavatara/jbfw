<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 12:59 AM
 */
class JBFWClassLoader {
	static public $exceptions = array();

	static public function addException($regexp) {
		self::$exceptions[] = $regexp;
	}

	static public function load($classname) {
		foreach(self::$exceptions as $ex) if (preg_match($ex, $classname)) return;
		preg_match('/(?<namespace>.+)\\\\(?<class>[^\\\\]+)$/', $classname, $matches);
		$path = strtolower(str_replace('\\', '/', $matches['namespace'])) . '/' . $matches['class'] . '.php';
		if ($path) require_once($path);
	}
}

spl_autoload_register(__NAMESPACE__ . '\JBFWClassLoader::load'); // As of PHP 5.3.0
