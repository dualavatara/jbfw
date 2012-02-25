<?php

namespace Admin;

/**
 * Simple HTTP request object.
 * Unify all parameters ($_GET, $_POST, parsed from url) into one data domain.
 * Modifying values after creation are restricted.
 */
class Request implements \ArrayAccess {

	/**
	 * @var array Internal data
	 */
	private $params;

	/**
	 * Constructor.
	 * Priority of different parameters type (from high to low):
	 * - $post
	 * - $get
	 * - $params
	 * 
	 * @param array $params Request parameters parsed from URL
	 * @param array $get    Get parameters
	 * @param array $post   Post parameters
	 */
	public function __construct(array $params = array(), array $get = array(), array $post = array()) {	
		$this->params = $post + $get + $params;
	}
	
	/**
	 * Create request from global variables ($_GET and $_POST)
	 * 
	 * @static
	 * @param array $params Request parameters parsed from URL
	 * 
	 * @return Request
	 */
	public static function createFromGlobals(array $params = array()) {
		return new static($params, $_GET, $_POST);
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->params);
	}

	public function offsetGet($offset) {
		return $this->params[$offset];
	}

	public function offsetSet($offset, $value) {
		throw new \BadMethodCallException('Can not modify request');
	}

	public function offsetUnset($offset) {
		throw new \BadMethodCallException('Can not modify request');		
	}
}