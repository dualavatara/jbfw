<?php
namespace model;

require_once 'model/RealtyModel.php';
require_once 'admin/lib/AdminModel.php';

class Realty extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \RealtyModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['description'] = new \TextAdminField('description','Описание', false);
		$this->fields['features'] = new \DefaultAdminField('features','Особенности', false);
		$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true, false, false, 10);
		$this->fields['rooms'] = new \DefaultAdminField('rooms','Комнат', false, false, false, 10);
		$this->fields['bedrooms'] = new \DefaultAdminField('bedrooms','Спален', false, false, false, 10);
		$this->fields['floor'] = new \DefaultAdminField('floor','Этаж', false, false, false, 10);
		$this->fields['total_floors'] = new \DefaultAdminField('total_floors','Этажность', false, false, false, 10);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Сортировка', true, false, false, 10);
		$this->fields['images'] = new \RefAdminField('images','Картинки', true);
		$this->fields['images']->class = 'RealtyImage';
		$this->fields['images']->parentField = 'realty_id';
		$this->fields['images']->fromRoute = 'realty_list';
	}
}
