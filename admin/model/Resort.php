<?php
namespace model;

require_once 'model/ResortModel.php';
require_once 'admin/lib/AdminModel.php';

class Resort extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \ResortModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['link'] = new \DefaultAdminField('link','Ссылка', true);
		$this->fields['gmaplink'] = new \DefaultAdminField('gmaplink','Google map link', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);

		$this->fields['places'] = new \RefAdminField('places','Места', new \ParentChildParams(array('parent_field' => 'resort_id')), true);
		$this->fields['places']->class = 'Place';
		$this->fields['places']->fromRoute = 'place_list';
	}
}
