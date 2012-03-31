<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 1:47 AM
 */

namespace View;

class BaseView implements IView {
	public $content;

	public function start() {
		ob_start();
	}

	public function end() {
		$this->content = ob_get_clean();
	}

	public function show() { return null; }
}
