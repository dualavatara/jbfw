<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');
require_once 'model/RealtyModel.php';
require_once 'model/CurrencyModel.php';

class PriceModel extends Model{

	const START_INVALID	= 0x0001;
	const END_INVALID		= 0x0002;

	const CLASS_UNDEFINED	= 0;
	const CLASS_REALTY		= 1;

	private $currency;

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field(new DateTimeWithTZField("start"));
		$this->field(new DateTimeWithTZField("end"));
		$this->field(new IntField("currency_id"));
		$this->field(new IntField("class_id"));
		$this->field(new IntField("object_id"));
		$this->field(new RealField("value"));

		$this->field(new FlagsField("flags"));

		$this->currency = new CurrencyModel($db);
	}

	public function getFlags() {
		return array(
			self::START_INVALID => 'Без начала',
			self::END_INVALID => 'Без конца'
		);
	}

	public static function getClassId($object) {
		if ($object instanceof RealtyModel) return self::CLASS_REALTY;
		return self::CLASS_UNDEFINED;
	}

	public function getCurrencies() {
		$ret = array();
		$this->currency->get()->all()->exec();
		foreach($this->currency as $curr) $ret[$curr->id] = $curr->name;
		return $ret;
	}
}
