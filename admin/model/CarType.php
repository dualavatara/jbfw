<?php
namespace model;

require_once 'model/CarTypeModel.php';
require_once 'admin/lib/AdminModel.php';

class CarType extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CarTypeModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
	}
}
