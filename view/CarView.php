<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 10:00 AM
 */

namespace View;

class CarView extends BaseView {
	public function show() {
		$this->start();
		echo "tetstet";
		$this->end();
		return parent::show();
	}
}
