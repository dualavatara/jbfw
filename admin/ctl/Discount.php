<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Discount extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'discount_list', 'Discount', $app);
	}

}