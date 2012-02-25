<?php

namespace Admin\Extension\Template\Decorator;

class ObjectDecorator extends BaseDecorator {

	public function __call($method, $args) {
		$value = call_user_func_array(array($this->value, $method), $args);

		return $this->escaper->escape($value);
	}

	public function __toString() {
		return $this->escaper->escape((string)$this->value);
	}

	public function __get($key) {
		return $this->escaper->escape($this->value->$key);
	}

	public function __isset($key) {
		return isset($this->value->$key);
	}
}