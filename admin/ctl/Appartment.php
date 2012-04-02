<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Appartment extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'appartment_list', 'Appartment', $app);
	}

}