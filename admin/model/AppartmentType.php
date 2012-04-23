<?php
namespace model;

require_once 'model/AppartmentTypeModel.php';
require_once 'admin/lib/AdminModel.php';

class AppartmentType extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \AppartmentTypeModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
	}
}
