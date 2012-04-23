<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 8:13 PM
 */

require_once 'lib/model.lib.php';

class RealtyTypeModel extends Model{
	const FLAG_SEARCH_RENT		= 0x0001;
	const FLAG_SEARCH_SELL		= 0x0002;
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('realty_type', $db);
		$this->field(new CharField('name'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			self::FLAG_SEARCH_RENT => 'В поиске аренды',
			self::FLAG_SEARCH_SELL => 'В поиске продажи',
		);
	}
}
