<?php

namespace Admin;

/**
 * Simple DI container.
 * 
 * Implements "instance on demand" pattern:
 * 1. Not sharable resources.
 * You can put closure in container, and it will be executed on each access.
 * 2. Sharable resources (alternative to singleton).
 * Use \Admin\Container::share() to make you resource sharable. It still will be created on first access.
 */
class Container implements \ArrayAccess {
	
	/**
	 * @var array Internal container data
	 */
	private $values = array();
	
	/**
	 * Prevent executing closure on each access.
	 * 
	 * @param \Closure $callable
	 * 
	 * @return \Closure
	 */
	function share(\Closure $callable) {
        return function ($c) use ($callable) {
            static $object;

            if (is_null($object)) {
                $object = $callable($c);
            }

            return $object;
        };
    }
	
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->values);
	}

	/**
	 * Returns object from container.
	 * If it is closure - result of it's execution will be returned
	 * 
	 * @param string $offset
	 * 
	 * @return mixed 
	 */
	public function offsetGet($offset) {
		return ($this->values[$offset] instanceof \Closure)
				? $this->values[$offset]($this)
				: $this->values[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->values[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->values[$offset]);
	}
}