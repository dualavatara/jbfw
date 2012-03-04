<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Resort extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'resort_list', 'Resort', $app);
	}

}