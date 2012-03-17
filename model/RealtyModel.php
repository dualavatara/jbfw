<?php
/**
 * User: dualavatara
 * Date: 3/17/12
 * Time: 8:49 PM
 */

require_once 'lib/model.lib.php';

class RealtyModel extends Model {
	const TYPE_VILLA	= 1;
	const TYPE_HOTEL	= 2;

	const FLAG_VISIBLE		= 0x0001;

	public function __construct(IDatabase $db) {
		parent::__construct('realty', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('features'));
		$this->field(new IntField('type'));
		$this->field(new IntField('rooms'));
		$this->field(new IntField('bedrooms'));
		$this->field(new IntField('floor'));
		$this->field(new IntField('total_floors'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));
		/*
Комнат
Спален
курорт
этаж
этажность
флаги (показывать)
ord
особенности (балкон, вид - текстом, дублируется в полях)
описание
фотки (субкласс, кросс-таблица)
апартаменты (субкласс, кросс-таблица)
		 */
	}

	public function getTypes() {
		return array(
			self::TYPE_VILLA => 'Вилла',
			self::TYPE_HOTEL => 'Отель'
		);
	}

	public function getFlags() {
		return array(
			self::FLAG_VISIBLE => 'Видимый',
		);
	}
}
