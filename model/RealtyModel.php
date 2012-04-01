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

	private $imgModel;
	private $resort;
	private $price;
	private $app;


	public function __construct(IDatabase $db) {
		parent::__construct('realty', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('features'));
		$this->field(new IntField('type'));
		$this->field(new IntField('rooms'));
		$this->field(new IntField('bedrooms'));
		$this->field(new IntField('floor'));
		$this->field(new IntField('total_floors'));
		$this->field(new IntField('stars'));
		$this->field(new IntField('resort_id'));
		$this->field(new IntField('ord'));
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

	public function loadDependecies() {
		$this->imgModel->get()->filter($this->imgModel->filterExpr()->eq('realty_id', $this->id))->exec();
		$this->resort->get()->filter($this->resort->filterExpr()->eq('id', $this->resort_id))->exec();
		$this->price->get()->filter($this->price->filterExpr()->
			eq('class_id', $this->price->getClassId($this))
			->_and()->eq('object_id', $this->id)
		)->exec();
	}

	public function getMainImage($id) {
		foreach($this->imgModel as $image) {
			if ($image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $id) return $image;
		}
	}

	public function getOtherImages($id) {
		$ret = array();
		foreach($this->imgModel as $image) {
			if (!$image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $id) $ret[] = $image;
		}
		return $ret;
	}

	public function getResort($id) {
		foreach($this->resort as $resort) {
			if ($resort->id == $id) return $resort;
		}
	}

	public function getPrices($id, DateTime $from, DateTime $to) {
		$ret = array();
		foreach($this->price as $price)
			if (($price->object_id == $id)
				&& ($price->isActive($from, $to))
			) $ret[] = $price;
		usort($ret, function($a, $b) {
			if ($a->value == $b->value) return 0;
			if ($a->value > $b->value) return 1; else return -1;
		});
		return $ret;
	}
	public function getAppartments($idx) {
		$this->app->get()->filter(
			$this->app->filterExpr()->eq('realty_id',$this[$idx]->id)
		)->exec();
		return $this->app;
	}

	public function getAppartmentPrices($idx) {
		$apps = $this->getAppartments($idx);
		$pres = array();
		foreach($apps as $app) {
			$prices = $app->getPrices(PriceModel::TYPE_RENT);
			if ($prices->count()) $pres[] = $prices[0];
		}

		usort($pres, function($a, $b) {
			if ($a->value == $b->value) return 0;
			if ($a->value > $b->value) return 1; else return -1;
		});
		return $pres;
	}
}
