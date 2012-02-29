<?php

namespace ctl;

require_once 'admin/lib/StdController.php';

class Currency extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'currency_list', 'Currency', $app);
	}

}