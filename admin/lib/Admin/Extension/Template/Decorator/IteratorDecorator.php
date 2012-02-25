<?php

namespace Admin\Extension\Template\Decorator;

use \Admin\Extension\Template\Escaper\Escaper;

class IteratorDecorator extends ObjectDecorator implements \Iterator {

	private $iterator;

	public function __construct(Escaper $escaper, \Traversable $value) {
		// Set the original value for __call(). Set our own iterator because passing
		// it to IteratorIterator will lose any other method calls.

		parent::__construct($escaper, $value);

		$this->iterator = new \IteratorIterator($value);
	}

	public function rewind() {
		return $this->iterator->rewind();
	}

	public function current() {
		return $this->escaper->escape($this->iterator->current());
	}

	public function key() {
		return $this->iterator->key();
	}

	public function next() {
		return $this->iterator->next();
	}

	public function valid() {
		return $this->iterator->valid();
	}
}