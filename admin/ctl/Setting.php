<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Setting extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('sys', 'setting_list', 'Setting', $app);
	}

}