<?php
namespace model;

require_once 'model/PlaceModel.php';
require_once 'admin/lib/AdminModel.php';

class Place extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \PlaceModel($db), '\ParentChildParams');
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['gps'] = new \DefaultAdminField('gps','Gps', true);

		$this->fields['resort_id'] = new \BackrefAdminField('resort_id', 'ID курорта', $_SESSION['urlparams']['parent_id'], false);
	}
}
