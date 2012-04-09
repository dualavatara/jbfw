<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 9:15 PM
 */

require_once 'lib/model.lib.php';

class BannerSize {
	public $width;
	public $height;

	function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}

}

/**
 *
 */
class BannerModel extends Model {
	/**
	 * Flags
	 */
	const FLAG_HEAD		= 0x0001;
	const FLAG_LEFTCOL	= 0x0002;
	const FLAG_VISIBLE	= 0x0004;

	const TYPE_240X100	= 1;
	const TYPE_240X350	= 2;

	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('banner', $db);

		$this->field(new CharField('image'));
		$this->field(new CharField('link'));
		$this->field(new IntField('type'));
		$this->field(new IntField('ord'));
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
			self::FLAG_VISIBLE => 'Видимый',
			self::FLAG_HEAD => 'Блок под шапкой',
			self::FLAG_LEFTCOL => 'Блок в левой колонке',
		);
	}

	public function getSize($type) {
		$width = 0;
		$height = 0;
		switch($type) {
			case BannerModel::TYPE_240X100:$width = 240;$height = 100;break;
			case BannerModel::TYPE_240X350:$width = 240;$height = 350;break;
		}
		return new BannerSize($width, $height);
	}

	public function getBannersHead($num) {
		$this->get()->filter($this->filterExpr()->
			eq('type', \BannerModel::TYPE_240X100)->
			_and()->eq('flags', \BannerModel::FLAG_HEAD)->
			_and()->eq('flags', \BannerModel::FLAG_VISIBLE)
		)->limit($num)->order('ord', true)->exec();
		return $this;
	}
}
