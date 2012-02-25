<?php

namespace Admin\Extension\Template\Escaper;

use \Admin\Extension\Template\Decorator\BaseDecorator;
use \Admin\Extension\Template\Decorator\ArrayDecorator;
use \Admin\Extension\Template\Decorator\ObjectDecorator;
use \Admin\Extension\Template\Decorator\IteratorDecorator;

class Escaper {
	
	private $escaper;
	
	/**
	 * @param callback $escaper
	 */
	public function __construct($escaper) {
		$this->escaper = $escaper;
	}
	
	public function escape($data) {

		if (null === $data) {
			return $data;
		}

		// Scalars are anything other than arrays, objects and resources.
		if (is_scalar($data)) {
			return call_user_func($this->escaper, $data);
		}

		if (is_array($data)) {
			return new ArrayDecorator($this, $data);
		}

		if (is_object($data)) {

			// avoid double escaping
			if ($data instanceof BaseDecorator) {
				return $data;
			}
			
			if ($data instanceof \Traversable) {
                return new IteratorDecorator($this, $data);
            }
			
			if ($data instanceof \Closure) {
				$me = $this;
				return function () use ($me, $data) {
					return $me->escape($data());	
				};
			}

			return new ObjectDecorator($this, $data);

		}

		// it must be a resource; cannot escape that.
		throw new \InvalidArgumentException(sprintf('Unable to escape value "%s".', var_export($data, true)));
	}
}