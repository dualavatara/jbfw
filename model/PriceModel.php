<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');

class PriceModel extends Model{

	const START_INVALID	= 0x0001;
	const END_INVALID		= 0x0002;

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field(new DateTimeWithTZField("start"));
		$this->field(new DateTimeWithTZField("end"));
		$this->field(new IntField("currency_id"));
		$this->field(new RealField("value"));

		$this->field(new FlagsField("flags"));
	}

	public function getFlags() {
		return array(
			self::START_INVALID => 'Без начала',
			self::END_INVALID => 'Без конца'
		);
	}
}
