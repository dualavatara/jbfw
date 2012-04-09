<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 2:49 AM
 */

require_once 'lib/model.lib.php';

class CarImageModel extends Model {
	const FLAG_MAIN		= 0x0001;

	public function __construct(IDatabase $db) {
		parent::__construct('car_image', $db);

		$this->field(new CharField('thumbnail'));
		$this->field(new CharField('thumbnail50'));
		$this->field(new CharField('thumbnail125'));
		$this->field(new CharField('thumbnail200'));
		$this->field(new CharField('image'));
		$this->field(new IntField('car_id'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			self::FLAG_MAIN => 'Главное',
		);
	}
}
