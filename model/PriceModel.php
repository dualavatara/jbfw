<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');

class PriceModel extends Model{

	const START_VALID	= 0x0001;
	const END_VALID		= 0x0002;

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field("start", new DateTimeWithTZField("start"));
		$this->field("end", new DateTimeWithTZField("end"));
		$this->field("currency_id", new IntField("currency_id"));
		$this->field("value", new RealField("value"));

		$this->field("flags", new IntField("flags"));
	}
}
