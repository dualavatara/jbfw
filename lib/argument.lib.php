<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g.sokolik
 * Date: 18.11.11
 * Time: 15:06
 */
 
abstract class Argument {
	protected $value;

	abstract function setValue($value);

	abstract function getValue();
}

class IntArgument extends Argument {

	function setValue($value) {
		$this->value = $value;
	}

	function getValue() {
		return $this->value;
	}
}

class StringArgument extends Argument {

	function setValue($value) {
		$this->value = $value;
	}

	function getValue() {
		return "'" . $this->value . "'";
	}
}
?>