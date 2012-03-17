<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Realty extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'realty_list', 'Realty', $app);
	}

}