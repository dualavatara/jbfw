<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 9:15 PM
 */

require_once 'lib/model.lib.php';

/**
 *
 */
class BannerModel extends Model {
	/**
	 * Flags
	 */
	const FLAG_HEAD		= 0x0001;
	const FLAG_LEFTCOL	= 0x0002;

	const TYPE_240X100	= 1;
	const TYPE_240X350	= 2;

	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('banner', $db);

		$this->field(new CharField('image'));
		$this->field(new IntField('type'));
		$this->field(new FlagsField('flags'));
	}

	/**
	 * @return array
	 */
	public function getTypes() {
		return array(
			self::TYPE_240X100 => '240x100',
			self::TYPE_240X350 => '240x350',
		);
	}

	/**
	 * @return array
	 */
	public function getFlags() {
		return array(
			self::FLAG_HEAD => 'Блок под шапкой',
			self::FLAG_LEFTCOL => 'Блок в левой колонке',
		);
	}
}
