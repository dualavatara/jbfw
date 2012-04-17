<?php

namespace Admin\Extension\Template\Decorator;

use \Admin\Extension\Template\Escaper\Escaper;

abstract class BaseDecorator {
	
	protected $value;
	/**
	 * @var Escaper
	 */
	protected $escaper;
	
	public function __construct(Escaper $escaper, $value) {
		$this->value = $value;
		$this->escaper = $escaper;
	}

	public function getRaw() {
		return $this->value;
	}
}