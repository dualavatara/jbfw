<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 6:35 PM
 */
namespace View;

class Tab extends BaseView {
	public $name;
	public $text;
	public $content;
	public $margin;

	public function __construct($name, $text, $content, $margin) {
		$this->name = $name;
		$this->text = $text;
		$this->content = $content;
		$this->margin = $margin;
	}

	public function show() {
		$this->start();
		$this->end();
		return parent::show();
	}
}
