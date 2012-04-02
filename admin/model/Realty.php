<?php
namespace model;

require_once 'model/RealtyModel.php';
require_once 'model/PriceModel.php';
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
		$this->fields['resort_id'] = new \SearchSelectAdminField('resort_id','Курорт', 'Resort', false);
		$this->fields['features'] = new \DefaultAdminField('features','Особенности', false);
		$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true, false, false, 10);
		$this->fields['rooms'] = new \DefaultAdminField('rooms','Комнат', false, false, false, 10);
		$this->fields['gmap'] = new \DefaultAdminField('gmap','Google maps IFRAME src', false, false, false, 60);
		$this->fields['condstate'] = new \DefaultAdminField('condstate','Состояние', false, false, false, 10);
		$this->fields['gmageap'] = new \DefaultAdminField('age','Время постройки', false, false, false, 10);
		$this->fields['bedrooms'] = new \DefaultAdminField('bedrooms','Спален', false, false, false, 10);
		$this->fields['floor'] = new \DefaultAdminField('floor','Этаж', false, false, false, 10);
		$this->fields['total_floors'] = new \DefaultAdminField('total_floors','Этажность', false, false, false, 10);
		$this->fields['are'] = new \DefaultAdminField('area','Площадь, м&#178;', false, false, false, 10);
		$this->fields['plotarea'] = new \DefaultAdminField('plotarea','Площадь участка, м&#178;', false, false, false, 10);
		$this->fields['stars'] = new \DefaultAdminField('stars','Звезд', false, false, false, 10);
		$this->fields['miscflags'] = new \CustomFlagsField('miscflags','Доп. опции', 'getMiscFlags', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Сортировка', true, false, false, 10);
		$this->fields['images'] = new \RefAdminField('images','Картинки', new \ParentChildParams(array('parent_field' => 'realty_id')), true);
		$this->fields['images']->class = 'RealtyImage';
		$this->fields['images']->fromRoute = 'realty_list';

		$this->fields['appartment'] = new \RefAdminField('appartment','Апартаменты', new \ParentChildParams(array('parent_field' => 'realty_id')), true);
		$this->fields['appartment']->class = 'Appartment';
		$this->fields['appartment']->fromRoute = 'realty_list';

		$this->fields['prices'] = new \RefAdminField('prices','Цены',
			new \ClassObjectChildParams(
				array(
					'class_field' => 'class_id',
					'object_field' => 'object_id',
					'class_id' => \PriceModel::getClassId($this->getModel()),
				)),
			true);
		$this->fields['prices']->class = 'Price';
		$this->fields['prices']->fromRoute = 'realty_list';
	}
}
