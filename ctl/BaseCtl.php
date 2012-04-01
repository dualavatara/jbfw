<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 1:05 AM
 */

namespace Ctl;

require_once 'lib/exception.lib.php';

abstract class BaseCtl {
	/**
	 * @var \IDispatcher
	 */
	protected $disp;

	function __construct(\IDispatcher $disp) {
		$this->disp = $disp;
	}

	abstract static public function link($method, $params);
}
