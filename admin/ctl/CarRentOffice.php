<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class CarRentOffice extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'carrentoffice_list', 'CarRentOffice', $app);
	}

}