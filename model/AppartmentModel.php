<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 2:51 AM
 */
require_once 'lib/model.lib.php';
require_once 'model/PriceModel.php';

class AppartmentModel extends Model {
	const TYPE_APPARTMENT	= 1;
	const TYPE_ROOM			= 2;

	const FLAG_VISIBLE		= 0x0001;

	private $price;

	public function __construct(IDatabase $db) {
		parent::__construct('appartment', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('features'));
		$this->field(new IntField('type'));
		$this->field(new IntField('realty_id'));
		$this->field(new IntField('rooms'));
		$this->field(new IntField('bedrooms'));
		$this->field(new IntField('floor'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));

		$this->price = new PriceModel($db);
	}

	public function getTypes() {
		return array(
			self::TYPE_APPARTMENT => 'Апартаменты',
			self::TYPE_ROOM => 'Комната'
		);
	}

	public function getFlags() {
		return array(
			self::FLAG_VISIBLE => 'Видимый',
		);
	}

	public function getPrices($idx, $type, DateTime $date) {
		$this->price->get()->filter(
			$this->price->filterExpr()->eq('class_id', PriceModel::CLASS_APPARTMENT)
			->_and()->eq('object_id', $this[$idx]->id)
			->_and()->eq('type', $type)
		)->filter(
			$this->price->filterExpr()->eq('flags', PriceModel::END_INVALID)->_or()->more('end', $date)
		)->order('value')->exec();
		return $this->price;
	}
}
