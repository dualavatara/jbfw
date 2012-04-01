<?php
namespace model;

require_once 'model/AppartmentModel.php';
require_once 'admin/lib/AdminModel.php';
require_once 'model/PriceModel.php';

class Appartment extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \AppartmentModel($db), '\ParentChildParams');
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['description'] = new \TextAdminField('description','Описание', true);
		$this->fields['features'] = new \DefaultAdminField('features','Особенности', true);
		//$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true, false, false, 10);
		$this->fields['rooms'] = new \DefaultAdminField('rooms','Комнат', true);
		$this->fields['bedrooms'] = new \DefaultAdminField('bedrooms','Спален', true);
		$this->fields['floor'] = new \DefaultAdminField('floor','Этаж', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Порядок', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);

		$this->fields['realty_id'] = new \BackrefAdminField('realty_id', 'ID объекта недвижимости', $_SESSION['urlparams']['parent_id'], false);

		$this->fields['prices'] = new \RefAdminField('prices','Цены',
			new \ClassObjectChildParams(
				array(
					'class_field' => 'class_id',
					'object_field' => 'object_id',
					'class_id' => \PriceModel::getClassId($this->getModel()),
				)),
			true);
		$this->fields['prices']->class = 'Price';
		$this->fields['prices']->fromRoute = 'appartment_list';
	}
}
