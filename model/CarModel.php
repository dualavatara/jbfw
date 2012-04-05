<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 1:00 AM
 */

require_once 'lib/model.lib.php';

class CarModel extends Model {
	const FLAG_VISIBLE 		=0x0001;
	const FLAG_SPUTNIK 		=0x0002;
	const FLAG_CONDITIONER 	=0x0004;
	const FLAG_DIESEL 		=0x0005;
	const FLAG_AUTOMAT 		=0x0010;
	const FLAG_BEST 		=0x0020;
	const FLAG_HIT 			=0x0040;
	const FLAG_DESCOUNT 	=0x0080;

	public function __construct(IDatabase $db) {
		parent::__construct('car', $db);

		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('age'));
		$this->field(new IntField('min_rent'));
		$this->field(new IntField('ord'));
		$this->field(new IntField('flags'));
		$this->field(new IntField('type_id'));
		$this->field(new IntField('fuel'));


		$this->field(new IntField('seats'));
		$this->field(new IntField('baggage'));
		$this->field(new IntField('doors'));
		$this->field(new IntField('min_age'));
		$this->field(new IntField('office_id'));
		$this->field(new IntField('customer_id'));
		$this->field(new RealField('volume')); //Объем двигателя (число)

		$this->field(new RealField('price_addseat'));
		$this->field(new RealField('price_insure'));
		$this->field(new RealField('price_franchise'));
		$this->field(new RealField('price_seat1'));
		$this->field(new RealField('price_seat2'));
		$this->field(new RealField('price_seat3'));
		$this->field(new RealField('price_chains'));
		$this->field(new RealField('price_navigator'));
		$this->field(new RealField('price_zalog'));

		$this->field(new RealField('discount1'));
		$this->field(new RealField('discount2'));
		$this->field(new RealField('discount3'));
		$this->field(new RealField('discount4'));
		$this->field(new RealField('discount5'));
		$this->field(new RealField('trans_airport'));
		$this->field(new RealField('trans_hotel'));
		$this->field(new RealField('trans_driver'));
		$this->field(new RealField('trans_dirty'));
	}
	public function getFlags() {
		return array(
			self::FLAG_VISIBLE 		=> 'Видимый',
			self::FLAG_SPUTNIK 		=> 'Система спутниковой навигации',
			self::FLAG_CONDITIONER 	=> 'Кондиционер',
			self::FLAG_DIESEL 		=> 'Дизельный автомобиль',
			self::FLAG_AUTOMAT 		=> 'Автоматическая трансмиссия',
			self::FLAG_BEST 		=> 'Лучшая цена',
			self::FLAG_HIT 			=> 'Хит',
			self::FLAG_DESCOUNT 	=> 'Скидки',
		);
	}
}
