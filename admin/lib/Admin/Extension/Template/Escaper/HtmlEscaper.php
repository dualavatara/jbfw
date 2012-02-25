<?php

namespace Admin\Extension\Template\Escaper;

class HtmlEscaper extends Escaper {
	
	public function __construct() {
		parent::__construct(array($this, 'escapeHtml'));
	}
	
	public function escapeHtml($value) {
		return is_string($value)
			? htmlspecialchars($value, ENT_QUOTES)
			: $value;
	}
}