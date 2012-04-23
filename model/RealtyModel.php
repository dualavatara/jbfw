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
require_once 'model/RealtyTypeModel.php';

class RealtyModel extends Model {

	const FLAG_VISIBLE = 0x0001;
	const FLAG_BEST = 0x0002;
	const FLAG_DISCOUNT = 0x0004;
	const FLAG_HIT = 0x0008;


	const MISCFLAG_SAFEDOOR = 0x0001;
	const MISCFLAG_GARDEN = 0x0002;
	const MISCFLAG_FIRSTLINE = 0x0004;

	public $imgModel;
	public $resort;
	public $price;
	public $app;
	public $type;


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
		$this->field(new IntField('adults'));
		$this->field(new IntField('kids'));
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
		$this->type = new RealtyTypeModel($db);
	}

	public function getTypes($flag = false) {
		$this->type->get();

		if (!$flag) $this->type->all();
		else $this->type->filter($this->type->filterExpr()->eq('flags', $flag));

		$this->type->exec();
		$res = array();
		foreach($this->type as $type) $res[$type->id] = $type->name;
		return $res;
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
			self::MISCFLAG_FIRSTLINE => 'Первая линия',
		);
	}

	public function loadDependecies() {
		$this->imgModel->get()->filter($this->imgModel->filterExpr()->eq('realty_id', $this->id))->exec();
		$this->resort->get()->filter($this->resort->filterExpr()->eq('id', $this->resort_id))->exec();
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $this->id))->exec();
		$this->app->get()->filter($this->app->filterExpr()->eq('realty_id', $this->id)->_and()->eq('flags', AppartmentModel::FLAG_VISIBLE))->exec();
	}

	public function filterType($typeId) {
		$newdata = array();
		foreach($this->data as $row) {
			if ($row['type'] == $typeId) $newdata[] = $row;
		}
		$this->data = $newdata;
	}

	public function filterByField($field, $func) {
		$newdata = array();
		foreach($this->data as $row) {
			if ($func($row[$field])) $newdata[] = $row;
		}
		$this->data = $newdata;
	}

	public function filterByPrice($type, $func) {
		$newdata = array();
		foreach($this->data as $row) {
			foreach($this->price as $price) {
				if ($price->class_id == $this->price->getClassId($this) && $price->object_id == $row['id']) {
					if ($price->type == $type && $func($price->calcValue(\Session::obj()->currency['course']))) $newdata[] = $row;
				}
			}
		}
		$this->data = $newdata;
	}


	public function filterHasApp() {
		$newdata = array();
		foreach($this->data as $row) {
			if ($this->hasApp($row['id'])) $newdata[] = $row;
		}
		$this->data = $newdata;
	}

	public function filterPricesDate($from, $to) {
		$newdata = array();
		foreach($this->data as $row) {
			foreach($this->price as $price) {
				if ($price->class_id == $this->price->getClassId($this) && $price->object_id == $row['id']) {
					if ($price->isActive(new DateTime($from), new DateTime($to))) $newdata[] = $row;
				}
			}
		}
		$this->data = $newdata;
	}

	public function hasApp($id) {
		foreach($this->app as $app) if ($app->realty_id == $id) return true;
		return false;
	}

	public function getMainImage($idx) {
		foreach ($this->imgModel as $image) {
			if ($image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $this[$idx]->id) return $image;
		}
	}

	public function getOtherImages($idx) {
		$ret = array();
		foreach ($this->imgModel as $image) {
			if (!$image->flags->check(RealtyImageModel::FLAG_MAIN) && $image->realty_id == $this[$idx]->id) $ret[] = $image;
		}
		return $ret;
	}

	public function getResort($idx) {
		$id = $this[$idx]->resort_id;
		foreach ($this->resort as $resort) {
			if ($resort->id == $id) return $resort;
		}
	}

	public function getAppartments($idx) {
		$this->app->get()->filter($this->app->filterExpr()->eq('realty_id', $this[$idx]->id))->exec();
		return $this->app;
	}

	public function getPrices($idx, $type = false) {
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $this[$idx]->id));
		if ($type) $this->price->filter($this->price->filterExpr()->eq('type', $type));
		$this->price->filter($this->price->filterExpr()->eq('flags', PriceModel::END_INVALID)->_or()
			->more('end', new DateTime()))->order('value')->exec();
		return $this->price;
	}

	public function getAppartmentPrices($idx) {
		$apps = $this->getAppartments($idx);
		$pres = array();
		foreach ($apps as $app) {
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
		$this->get()->filter($this->filterExpr()->eq('flags', self::FLAG_VISIBLE)->_and()->eq(MODEL_ID_FIELD_NAME, $id))
			->exec();
		if (!$this->count()) throw new NotFoundException();
		return $this[0];
	}

	public function getList($flags = array(), $sort = array()) {
		$filter = $this->filterExpr()->eq('flags', \RealtyModel::FLAG_VISIBLE);
		foreach ($flags as $flag) $filter->_and()->eq('flags', $flag);

		$this->get()->filter($filter)->exec();

		$dir = 1;
		$sortFuncs = array(
			'ord' => function($a, $b) use (&$dir) {
				if ($a['ord'] == $b['ord']) return 0;
				$r = 0;
				if ($a['ord'] > $b['ord']) $r = 1; else $r = -1;
				$r = $r * -$dir;
				return $r;
			}, 'price' => function($a, $b) use (&$dir) {
				if ($a['price_tmp'] == $b['price_tmp']) return 0;
				$r = 0;
				if ($a['price_tmp'] > $b['price_tmp']) $r = 1; else $r = -1;
				$r = $r * -$dir;
				return $r;
			},
		);

		//prepare prices
		foreach ($this as $realty) {
			$price = $realty->getAppartmentPrices();
			$priceValue = PHP_INT_MAX;
			if (!empty($price)) {
				$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
			}
			$this->data[$realty->getOffset()]['price_tmp'] = $priceValue;
		}
		foreach ($sort as $sk => $sv) {
			$dir = $sv;
			if (isset($sortFuncs[$sk])) usort($this->data, $sortFuncs[$sk]);
		}
		return $this;
	}
}
