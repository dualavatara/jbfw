<?php
/**
 * User: dualavatara
 * Date: 3/11/12
 * Time: 9:03 PM
 */

require_once 'lib/singletone.lib.php';

class Session extends Singletone {
	protected function __construct() {
		parent::__construct();
		session_start();
	}

	function __get($name) {
		return $_SESSION[$name];
	}

	function __set($name, $value) {
		$_SESSION[$name] = $value;
	}

	function __isset($name) {
		return isset($_SESSION[$name]);
	}

	function __unset($name) {
		unset($_SESSION[$name]);
	}

}
