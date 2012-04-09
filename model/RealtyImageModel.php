<?php
/**
 * User: dualavatara
 * Date: 3/18/12
 * Time: 12:15 AM
 */

require_once 'lib/model.lib.php';

class RealtyImageModel extends  Model {
	const FLAG_MAIN		= 0x0001;

	public function __construct(IDatabase $db) {
		parent::__construct('realty_image', $db);

		$this->field(new CharField('thumbnail'));
		$this->field(new CharField('thumbnail50'));
		$this->field(new CharField('thumbnail125'));
		$this->field(new CharField('thumbnail200'));
		$this->field(new CharField('image'));
		$this->field(new IntField('realty_id'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			self::FLAG_MAIN => 'Главное',
		);
	}
}
