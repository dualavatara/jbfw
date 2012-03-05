<?php
namespace ctl;

require_once 'model/CustomerModel.php';
require_once 'admin/lib/StdController.php';

class CarRentOffice extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'carrentoffice_list', 'CarRentOffice', $app);

		$cm = new \CustomerModel($this->app['db']);
		$cm->get()->all()->exec();

		foreach($cm as $row) {
			$this->data['customers'][$row->id] = $row->name;
		}
	}

}