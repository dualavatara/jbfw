<?php
/**
 * User: dualavatara
 * Date: 3/17/12
 * Time: 8:49 PM
 */

require_once 'lib/model.lib.php';
require_once 'model/RealtyImageModel.php';
require_once 'model/ResortModel.php';
require_once 'model/PriceModel.php';
require_once 'model/AppartmentModel.php';

class RealtyModel extends Model {
	const TYPE_VILLA	= 1;
	const TYPE_HOTEL	= 2;

	const FLAG_VISIBLE		= 0x0001;
	const FLAG_BEST			= 0x0002;
	const FLAG_DISCOUNT		= 0x0004;
	const FLAG_HIT			= 0x0008;

	const MISCFLAG_SAFEDOOR			= 0x0001;
	const MISCFLAG_GARDEN			= 0x0002;

	private $imgModel;
	private $resort;
	private $price;
	private $app;


	public function __construct(IDatabase $db) {
		parent::__construct('realty', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('features'));
		$this->field(new CharField('gmap'));
		$this->field(new CharField('condstate'));
		$this->field(new CharField('age'));
		$this->field(new IntField('type'));
		$this->field(new IntField('rooms'));
		$this->field(new IntField('bedrooms'));
		$this->field(new CharField('floor'));
		$this->field(new IntField('total_floors'));
		$this->field(new IntField('area'));
		$this->field(new IntField('plotarea'));
		$this->field(new IntField('stars'));
		$this->field(new IntField('resort_id'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('miscflags'));
		$this->field(new FlagsField('flags'));

		$this->imgModel = new RealtyImageModel($db);
		$this->resort = new ResortModel($db);
		$this->price = new PriceModel($db);
		$this->app = new AppartmentModel($db);
	}

	public function getTypes() {
		return array(
			self::TYPE_VILLA => 'Вилла',
			self::TYPE_HOTEL => 'Отель'
		);
	}

	public function getFlags() {
		return array(
			self::FLAG_VISIBLE => 'Видимый',
			self::FLAG_BEST => 'Лучшее предложение',
			self::FLAG_DISCOUNT => 'Скидка',
			self::FLAG_HIT => 'Хит',
		);
	}
	public function getMiscFlags() {
		return array(
			self::MISCFLAG_SAFEDOOR => 'Сейф дверь',
			self::MISCFLAG_GARDEN => 'Сад',
		);
	}

	public function loadDependecies() {
		$this->imgModel->get()->filter($this->imgModel->filterExpr()->eq('realty_id', $this->id))->exec();
		$this->resort->get()->filter($this->resort->filterExpr()->eq('id', $this->resort_id))->exec();
		$this->price->get()->filter($this->price->filterExpr()->
			eq('class_id', $this->price->getClassId($this))
			->_and()->eq('object_id', $this->id)
		)->exec();
	}

	public function getMainImage($idx) {
		foreach($this->imgModel as $image) {
			if ($image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $this[$idx]->id) return $image;
		}
	}

	public function getOtherImages($idx) {
		$ret = array();
		foreach($this->imgModel as $image) {
			if (!$image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $this[$idx]->id) $ret[] = $image;
		}
		return $ret;
	}

	public function getResort($id) {
		foreach($this->resort as $resort) {
			if ($resort->id == $id) return $resort;
		}
	}

	public function getAppartments($idx) {
		$this->app->get()->filter(
			$this->app->filterExpr()->eq('realty_id',$this[$idx]->id)
		)->exec();
		return $this->app;
	}

	public function getPrices($idx, $type = false) {
		$this->price->get()->filter(
			$this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))
				->_and()->eq('object_id', $this[$idx]->id)
		);
		if ($type) $this->price->filter($this->price->filterExpr()->eq('type', $type));
		$this->price->filter(
			$this->price->filterExpr()->eq('flags', PriceModel::END_INVALID)->_or()->more('end', new DateTime())
		)->order('value')->exec();
		return $this->price;
	}

	public function getAppartmentPrices($idx) {
		$apps = $this->getAppartments($idx);
		$pres = array();
		foreach($apps as $app) {
			$prices = $app->getPrices();
			if ($prices->count()) $pres[] = $prices[0];
		}

		$prices = $this->getPrices($idx);
		if ($prices->count()) $pres[] = $prices[0];

		usort($pres, function($a, $b) {
			if ($a->value == $b->value) return 0;
			if ($a->value > $b->value) return 1; else return -1;
		});
		return $pres;
	}

	public function getRealty($id) {
		$this->get()->filter(
			$this->filterExpr()->eq('flags', self::FLAG_VISIBLE)->_and()->eq(MODEL_ID_FIELD_NAME, $id)
		)->exec();
		if (!$this->count()) throw new NotFoundException();
		return $this[0];
	}
}
