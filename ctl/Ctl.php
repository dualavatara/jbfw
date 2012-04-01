<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 1:05 AM
 */

namespace Ctl;

class Ctl {
	/**
	 * @var \IDispatcher
	 */
	protected $disp;

	function __construct(\IDispatcher $disp) {
		$this->disp = $disp;
	}
}
