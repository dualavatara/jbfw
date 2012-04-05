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
		$this->fields['id'] = new \DefaultAdminField('id','Id', true);
		$this->fields['name'] = new \DefaultAdminField('name','Name', true);
		$this->fields['description'] = new \DefaultAdminField('description','Description', true);
		$this->fields['age'] = new \DefaultAdminField('age','Age', true);
		$this->fields['min_rent'] = new \DefaultAdminField('min_rent','Min_rent', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Ord', true);
		$this->fields['flags'] = new \DefaultAdminField('flags','Flags', true);
		$this->fields['type_id'] = new \DefaultAdminField('type_id','Type_id', true);
		$this->fields['fuel'] = new \DefaultAdminField('fuel','Fuel', true);
		$this->fields['seats'] = new \DefaultAdminField('seats','Seats', true);
		$this->fields['baggage'] = new \DefaultAdminField('baggage','Baggage', true);
		$this->fields['doors'] = new \DefaultAdminField('doors','Doors', true);
		$this->fields['min_age'] = new \DefaultAdminField('min_age','Min_age', true);
		$this->fields['office_id'] = new \DefaultAdminField('office_id','Office_id', true);
		$this->fields['customer_id'] = new \DefaultAdminField('customer_id','Customer_id', true);
		$this->fields['volume'] = new \DefaultAdminField('volume','Volume', true);
		$this->fields['price_addseat'] = new \DefaultAdminField('price_addseat','Price_addseat', true);
		$this->fields['price_insure'] = new \DefaultAdminField('price_insure','Price_insure', true);
		$this->fields['price_franchise'] = new \DefaultAdminField('price_franchise','Price_franchise', true);
		$this->fields['price_seat1'] = new \DefaultAdminField('price_seat1','Price_seat1', true);
		$this->fields['price_seat2'] = new \DefaultAdminField('price_seat2','Price_seat2', true);
		$this->fields['price_seat3'] = new \DefaultAdminField('price_seat3','Price_seat3', true);
		$this->fields['price_chains'] = new \DefaultAdminField('price_chains','Price_chains', true);
		$this->fields['price_navigator'] = new \DefaultAdminField('price_navigator','Price_navigator', true);
		$this->fields['price_zalog'] = new \DefaultAdminField('price_zalog','Price_zalog', true);
		$this->fields['discount1'] = new \DefaultAdminField('discount1','Discount1', true);
		$this->fields['discount2'] = new \DefaultAdminField('discount2','Discount2', true);
		$this->fields['discount3'] = new \DefaultAdminField('discount3','Discount3', true);
		$this->fields['discount4'] = new \DefaultAdminField('discount4','Discount4', true);
		$this->fields['discount5'] = new \DefaultAdminField('discount5','Discount5', true);
		$this->fields['trans_airport'] = new \DefaultAdminField('trans_airport','Trans_airport', true);
		$this->fields['trans_hotel'] = new \DefaultAdminField('trans_hotel','Trans_hotel', true);
		$this->fields['trans_driver'] = new \DefaultAdminField('trans_driver','Trans_driver', true);
		$this->fields['trans_dirty'] = new \DefaultAdminField('trans_dirty','Trans_dirty', true);
	}
}
