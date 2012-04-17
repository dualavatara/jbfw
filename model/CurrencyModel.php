<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 4:04
 */

require_once 'lib/model.lib.php';

class CurrencyModel extends Model {
	const FLAG_DEFAULT		= 0x0001;
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('currency', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('sign'));
		$this->field(new RealField('course'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			self::FLAG_DEFAULT => 'По умолчанию',
		);
	}
}
