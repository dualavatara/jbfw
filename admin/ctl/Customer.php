<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Customer extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'customer_list', 'Customer', $app);
	}

}