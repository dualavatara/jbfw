<?php

namespace Admin\Extension\Template\Decorator;

class ArrayDecorator 
	extends BaseDecorator
	implements \ArrayAccess, \Iterator, \Countable 
{

	private $count = 0;

	
	// Iterator interface implementation
	public function rewind() {
		reset($this->value);

		$this->count = count($this->value);
	}

	public function key() {
		return key($this->value);
	}

	public function current() {
		return $this->escaper->escape(current($this->value));
	}

	public function next() {
		next($this->value);

		$this->count--;
	}

	public function valid() {
		return $this->count > 0;
	}
	
	
	// ArrayAccess interface implementation
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->value);
	}

	public function offsetGet($offset) {
		return $this->escaper->escape($this->value[$offset]);
	}

	public function offsetSet($offset, $value) {
		throw new \BadMethodCallException('Cannot set values in decorator.');
	}

	public function offsetUnset($offset) {
		throw new \BadMethodCallException('Cannot unset values in decorator.');
	}

	
	// Countable interface implementation
	public function count() {
		return count($this->value);
	}
}