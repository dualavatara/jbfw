<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 8:54 PM
 */
namespace View\Form;

class Field {
	public $label;
	public $name;
	public $padding;

	public $formname;
	public $value;

	public function __construct($label, $name, $padding = false) {
		$this->label = $label;
		$this->name = $name;
		$this->padding = $padding;
	}

	public function fieldName($prefix = '', $idx = '') {
		$res = $this->formname . '[' . $prefix . $this->name . ']';
		if ($idx) $res .= '[' . $idx . ']';
		return $res;
	}
}
