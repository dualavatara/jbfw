<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Beach extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'beach_list', 'Beach', $app);
	}

}