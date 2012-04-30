<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');
require_once 'model/RealtyModel.php';
require_once 'model/CurrencyModel.php';

class PriceModel extends Model {

	const START_INVALID = 0x0001;
	const END_INVALID = 0x0002;

	const CLASS_UNDEFINED = 0;
	const CLASS_REALTY = 1;
	const CLASS_APPARTMENT = 2;
	const CLASS_CAR = 3;

	const TYPE_RENT	= 1;
	const TYPE_SELL	= 2;

	private $currency;

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field(new DateTimeWithTZField("start"));
		$this->field(new DateTimeWithTZField("end"));
		$this->field(new IntField("currency_id"));
		$this->field(new IntField("class_id"));
		$this->field(new IntField("object_id"));
		$this->field(new IntField("type"));
		$this->field(new RealField("value"));
		$this->field(new RealField("week_disc"));
		$this->field(new RealField("month_disc"));

		$this->field(new FlagsField("flags"));

		$this->currency = new CurrencyModel($db);
		$this->currency->get()->all()->exec();
	}

	public function getFlags() {
		return array(
			self::START_INVALID => 'Без начала', self::END_INVALID => 'Без конца'
		);
	}

	public function getTypes() {
		return array(
			self::TYPE_SELL => 'Продажа',
			self::TYPE_RENT => 'Аренда (за день)'
		);
	}

	public function calcValue($idx, $newCourse) {
		$row = $this[$idx];
		/*$c = $row->getCurrency();
		if (!isset($c)) return '';
		$oldCourse = $c->course;*/
		$ret = ceil(floatval($row->value) / $newCourse);
		return sprintf("%d", $ret);
	}

	public static function getClassId($object) {
		if ($object instanceof RealtyModel) return self::CLASS_REALTY;
		if ($object instanceof AppartmentModel) return self::CLASS_APPARTMENT;
		if ($object instanceof CarModel) return self::CLASS_CAR;
		return self::CLASS_UNDEFINED;
	}

	/*public function getCurrency($idx) {
		foreach ($this->currency as $curr) if ($this[$idx]->currency_id == $curr->id) return $curr;
		return null;
	}*/

	public function getCurrencies() {
		$ret = array();
		$this->currency->get()->all()->exec();
		foreach ($this->currency as $curr) $ret[$curr->id] = $curr->name;
		return $ret;
	}

	public function isActive($idx, DateTime $from, DateTime $to) {
		$price = $this[$idx];
		$startHits = false;
		$endHits = false;
		if ($price->flags->check(self::START_INVALID) || ($price->start >= $from && $price->start <= $to)) $startHits = true;
		if ($price->flags->check(self::END_INVALID) || ($price->end >= $from && $price->end <= $to)) $endHits = true;
		return $startHits || $endHits;
	}
}
