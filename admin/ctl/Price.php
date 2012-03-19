<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Price extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'price_list', 'Price', $app);
	}

}