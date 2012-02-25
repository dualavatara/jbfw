<?php

namespace Admin;

/**
 * Object oriented wrapper around array.
 * 
 * Usage example:
 * <code>
 *   $data = array(
 *     'foo' => 'bar',
 *     'test' => array('one' => 1, 'two' => 2)
 *   );
 *   $config = new \Admin\Config($data);
 *   echo $config->test->one; // 1
 * </code>
 */
class Config extends \stdClass implements \IteratorAggregate, \ArrayAccess {

	/**
	 * @var array Configuration data
	 */
	private $config;

	public function __construct(array $array) {
		$this->config = $array;
	}

	/**
	 * @uses \Admin\Config::offsetGet()
	 */
	public function __get($name) {
		return $this[$name];
	}
	
	public function __set($name, $value) {
		$this->config[$name] = $value;
	}

	public function getIterator() {
		return new \ArrayIterator($this->config);
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->config);
	}


	/**
	 * Returns value for key if scalar, either new instance initialized with value.
	 * 
	 * @throws \OutOfBoundsException If tried to get value for non existing key.
	 * 
	 * @param string $offset
	 * 
	 * @return mixed
	 */
	public function offsetGet($offset) {
		if (!isset($this->config[$offset])) {
			$message = sprintf('Undefined key "%s" in config', $offset);
			throw new \OutOfBoundsException($message);
		}

		$value = $this->config[$offset];
		
		return is_array($value)
				? new Config($value)
				: $value;
	}

	public function offsetSet($offset, $value) {
		$this->config[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->config[$offset]);
	}
}