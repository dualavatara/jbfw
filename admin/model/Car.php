<?php
namespace model;

require_once 'model/CarModel.php';
require_once 'model/PriceModel.php';
require_once 'admin/lib/AdminModel.php';

class Car extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CarModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['name'] = new \DefaultAdminField('name','Название', true, true);
		$this->fields['description'] = new \TextAdminField('description','Описание', false);
		$this->fields['age'] = new \DefaultAdminField('age','Год выпуска', true);
		$this->fields['min_rent'] = new \DefaultAdminField('min_rent','Мин. дней аренды', true, false, false, 10);

		$this->fields['type_id'] = new \SearchSelectAdminField('type_id','Тип', 'CarType', true);
		$this->fields['fuel'] = new \DefaultAdminField('fuel','Расход топлива, л', false, false, 10);
		$this->fields['seats'] = new \DefaultAdminField('seats','Кол-во пассажиров', false, false, 10);
		$this->fields['baggage'] = new \DefaultAdminField('baggage','Кол-во багажа', false, false, 10);
		$this->fields['doors'] = new \DefaultAdminField('doors','Дверей', false, false, 10);
		$this->fields['min_age'] = new \DefaultAdminField('min_age','Мин. возраст', false, false, 10);
		$this->fields['office_id'] = new \SearchSelectAdminField('office_id','Контора проката', 'CarRentOffice', true);
		$this->fields['customer_id'] = new \SearchSelectAdminField('customer_id','Клиент', 'Customer', true);
		$this->fields['volume'] = new \FloatAdminField('volume','Объем двигалетя');
		$this->fields['price_addseat'] = new \FloatAdminField('price_addseat','Стоимость доп. пассажира, руб.');
		$this->fields['price_insure'] = new \FloatAdminField('price_insure','Страховка, руб.');
		$this->fields['price_insure'] = new \FloatAdminField('zalog_percent','Залог, %');
		$this->fields['price_franchise'] = new \FloatAdminField('price_franchise','Франшиза, руб.');
		$this->fields['price_seat1'] = new \FloatAdminField('price_seat1','Стоимость автокресла 0+, руб.');
		$this->fields['price_seat2'] = new \FloatAdminField('price_seat2','Стоимость автокресла 1, руб.');
		$this->fields['price_seat3'] = new \FloatAdminField('price_seat3','Стоимость автокресла 2-3, руб.');
		$this->fields['price_chains'] = new \FloatAdminField('price_chains','Стоимость цепей, руб.');
		$this->fields['price_navigator'] = new \FloatAdminField('price_navigator','Стоимость нафигатора, руб.');
		$this->fields['price_zalog'] = new \FloatAdminField('price_zalog','Стоимость залога, руб.');
		$this->fields['discount1'] = new \FloatAdminField('discount1','Скидка 3-6, руб.');
		$this->fields['discount2'] = new \FloatAdminField('discount2','Скидка 7-8, руб.');
		$this->fields['discount3'] = new \FloatAdminField('discount3','Скидка 9-15, руб.');
		$this->fields['discount4'] = new \FloatAdminField('discount4','Скидка 16-29, руб.');
		$this->fields['discount5'] = new \FloatAdminField('discount5','Скидка 30, руб.');
		$this->fields['trans_airport'] = new \FloatAdminField('trans_airport','Стоимость трансфера в аэропорт, руб.');
		$this->fields['trans_hotel'] = new \FloatAdminField('trans_hotel','Стоимость трансфера в гостиницу, руб.');
		$this->fields['trans_driver'] = new \FloatAdminField('trans_driver','Стоимость водителя в сутки, руб.');
		$this->fields['trans_dirty'] = new \FloatAdminField('trans_dirty','Стоимость при грязной машине, руб.');

		$this->fields['ord'] = new \DefaultAdminField('ord','Ord', true, false, false, 10);
		$this->fields['flags'] = new \FlagsAdminField('flags','Flags', true);

		$this->fields['prices'] = new \RefAdminField('prices','Цены',
			new \ClassObjectChildParams(
				array(
					'class_field' => 'class_id',
					'object_field' => 'object_id',
					'class_id' => \PriceModel::getClassId($this->getModel()),
				)),
			true);
		$this->fields['prices']->class = 'Price';
		$this->fields['prices']->fromRoute = 'car_list';

		$this->fields['images'] = new \RefAdminField('images','Картинки', new \ParentChildParams(array('parent_field' => 'car_id')), true);
		$this->fields['images']->class = 'CarImage';
		$this->fields['images']->fromRoute = 'car_list';
	}
}
