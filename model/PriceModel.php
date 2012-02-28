<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');

class PriceModel extends Model{

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field("start", new DateTimeWithTZField("start"));
		$this->field("end", new DateTimeWithTZField("end"));
		$this->field("currency_id", new IntField("currency_id"));
		$this->field("value", new RealField("value"));

		$this->field("flags", new IntField("flags"));
	}
}
