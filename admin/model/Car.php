<?php
namespace model;

require_once 'model/CarModel.php';
require_once 'admin/lib/AdminModel.php';

class Car extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CarModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Name', true, true);
		$this->fields['description'] = new \TextAdminField('description','Description', false);
		$this->fields['age'] = new \DefaultAdminField('age','Age', true);
		$this->fields['min_rent'] = new \DefaultAdminField('min_rent','Min_rent', true, false, false, 10);

		$this->fields['type_id'] = new \SearchSelectAdminField('type_id','Type_id', 'CarType', true);
		$this->fields['fuel'] = new \DefaultAdminField('fuel','Fuel', false, false, 10);
		$this->fields['seats'] = new \DefaultAdminField('seats','Seats', false, false, 10);
		$this->fields['baggage'] = new \DefaultAdminField('baggage','Baggage', false, false, 10);
		$this->fields['doors'] = new \DefaultAdminField('doors','Doors', false, false, 10);
		$this->fields['min_age'] = new \DefaultAdminField('min_age','Min_age', false, false, 10);
		$this->fields['office_id'] = new \SearchSelectAdminField('office_id','Office_id', 'CarRentOffice', true);
		$this->fields['customer_id'] = new \SearchSelectAdminField('customer_id','Customer_id', 'Customer', true);
		$this->fields['volume'] = new \FloatAdminField('volume','Volume');
		$this->fields['price_addseat'] = new \FloatAdminField('price_addseat','Price_addseat');
		$this->fields['price_insure'] = new \FloatAdminField('price_insure','Price_insure');
		$this->fields['price_franchise'] = new \FloatAdminField('price_franchise','Price_franchise');
		$this->fields['price_seat1'] = new \FloatAdminField('price_seat1','Price_seat1');
		$this->fields['price_seat2'] = new \FloatAdminField('price_seat2','Price_seat2');
		$this->fields['price_seat3'] = new \FloatAdminField('price_seat3','Price_seat3');
		$this->fields['price_chains'] = new \FloatAdminField('price_chains','Price_chains');
		$this->fields['price_navigator'] = new \FloatAdminField('price_navigator','Price_navigator');
		$this->fields['price_zalog'] = new \FloatAdminField('price_zalog','Price_zalog');
		$this->fields['discount1'] = new \FloatAdminField('discount1','Discount1');
		$this->fields['discount2'] = new \FloatAdminField('discount2','Discount2');
		$this->fields['discount3'] = new \FloatAdminField('discount3','Discount3');
		$this->fields['discount4'] = new \FloatAdminField('discount4','Discount4');
		$this->fields['discount5'] = new \FloatAdminField('discount5','Discount5');
		$this->fields['trans_airport'] = new \FloatAdminField('trans_airport','Trans_airport');
		$this->fields['trans_hotel'] = new \FloatAdminField('trans_hotel','Trans_hotel');
		$this->fields['trans_driver'] = new \FloatAdminField('trans_driver','Trans_driver');
		$this->fields['trans_dirty'] = new \FloatAdminField('trans_dirty','Trans_dirty');

		$this->fields['ord'] = new \DefaultAdminField('ord','Ord', true, false, false, 10);
		$this->fields['flags'] = new \FlagsAdminField('flags','Flags', true);

		$this->fields['images'] = new \RefAdminField('images','Картинки', new \ParentChildParams(array('parent_field' => 'car_id')), true);
		$this->fields['images']->class = 'CarImage';
		$this->fields['images']->fromRoute = 'car_list';
	}
}
