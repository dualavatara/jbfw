<?php
namespace model;

require_once 'model/CarRentOfficeModel.php';
require_once 'admin/lib/AdminModel.php';

class CarRentOffice extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CarRentOfficeModel($db));
	}
}
