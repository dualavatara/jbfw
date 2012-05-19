<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 1:00 AM
 */

require_once 'lib/model.lib.php';
require_once 'model/PriceModel.php';
require_once 'model/CarImageModel.php';
require_once 'model/CarTypeModel.php';
require_once 'model/CarRentOfficeModel.php';

class CarModel extends Model {
	const FLAG_VISIBLE 		=0x0001;
	const FLAG_SPUTNIK 		=0x0002;
	const FLAG_CONDITIONER 	=0x0004;
	const FLAG_DIESEL 		=0x0008;
	const FLAG_AUTOMAT 		=0x0010;
	const FLAG_BEST 		=0x0020;
	const FLAG_HIT 			=0x0040;
	const FLAG_DESCOUNT 	=0x0080;
	const FLAG_CAMERA	 	=0x0100;
	const FLAG_PASSPORT		=0x0200;


	const RIFLAG_INSURE			=0x0001;
	const RIFLAG_INSURETHIRD	=0x0002;
	const RIFLAG_INSUREROBBERY	=0x0004;
	const RIFLAG_TAX			=0x0008;
	const RIFLAG_GREENCARD		=0x0010;
	const RIFLAG_AVIA			=0x0020;
	const RIFLAG_UNLIMITED		=0x0040;
	const RIFLAG_HELP			=0x0080;
	/**
	 * @var PriceModel
	 */
	public $price;

	/**
	 * @var CarImageModel
	 */
	public $image;

	public $type;
	public $carrentoffice;

	public function __construct(IDatabase $db) {
		parent::__construct('car', $db);

		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('age'));
		$this->field(new IntField('min_rent'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));
		$this->field(new FlagsField('rent_include_flags'));
		$this->field(new IntField('type_id'));
		$this->field(new IntField('fuel'));
		$this->field(new IntField('resort_id'));
		$this->field(new IntField('place_id'));


		$this->field(new IntField('seats'));
		$this->field(new IntField('baggage'));
		$this->field(new IntField('doors'));
		$this->field(new IntField('min_age'));
		$this->field(new IntField('min_exp'));
		$this->field(new IntField('office_id'));
		$this->field(new IntField('customer_id'));
		$this->field(new RealField('volume')); //Объем двигателя (число)

		$this->field(new RealField('price_addseat'));
		$this->field(new RealField('price_insure'));
		$this->field(new RealField('zalog_percent'));
		$this->field(new RealField('price_franchise'));
		$this->field(new RealField('price_seat1'));
		$this->field(new RealField('price_seat2'));
		$this->field(new RealField('price_seat3'));
		$this->field(new RealField('price_chains'));
		$this->field(new RealField('price_navigator'));
		$this->field(new RealField('price_zalog'));

		$this->field(new RealField('discount1'));
		$this->field(new RealField('discount2'));
		$this->field(new RealField('discount3'));
		$this->field(new RealField('discount4'));
		$this->field(new RealField('discount5'));
		$this->field(new RealField('trans_airport'));
		$this->field(new RealField('trans_hotel'));
		$this->field(new RealField('trans_driver'));
		$this->field(new RealField('trans_dirty'));

		$this->price = new PriceModel($db);
		$this->image = new CarImageModel($db);
		$this->type = new CarTypeModel($db);
	}
	public function getFlags() {
		return array(
			self::FLAG_VISIBLE 		=> 'Видимый',
			self::FLAG_SPUTNIK 		=> 'Система спутниковой навигации',
			self::FLAG_CONDITIONER 	=> 'Кондиционер',
			self::FLAG_DIESEL 		=> 'Дизельный автомобиль',
			self::FLAG_AUTOMAT 		=> 'Автоматическая трансмиссия',
			self::FLAG_CAMERA		=> 'Камера заднего вида',
			self::FLAG_PASSPORT		=> 'Обязательное наличие паспорта',
			self::FLAG_BEST 		=> 'Лучшая цена',
			self::FLAG_HIT 			=> 'Хит',
			self::FLAG_DESCOUNT 	=> 'Скидки',
		);
	}

	public function getRentIncludedFlags() {
		return array(
			self::RIFLAG_INSURE		=> 'Страхование ущерба',
			self::RIFLAG_INSURETHIRD	=> 'Страхование третьего лица',
			self::RIFLAG_INSUREROBBERY=> 'Страхование от ограбления',
			self::RIFLAG_TAX			=> 'Налог',
			self::RIFLAG_GREENCARD	=> 'Green карта',
			self::RIFLAG_AVIA			=> 'Авиасбор',
			self::RIFLAG_UNLIMITED	=> 'Неограниченный пробег',
			self::RIFLAG_HELP			=> 'Помощь в разбивке счета',
		);
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

	public function getList($flags = array(), $sort = array(), $priceType = false) {
		$filter = $this->filterExpr()->eq('flags', self::FLAG_VISIBLE);
		foreach ($flags as $flag) $filter->_and()->eq('flags', $flag);

		$this->get()->filter($filter)->exec();

		$p = $this->getPricesId($this->id, $priceType);
		$this->data = array_filter($this->data, function($row) use($p) {
			foreach($p as $price) if ($price->object_id == $row['id']) return true;
			return false;
		});

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
		foreach ($this as $car) {
			$price = $car->getPrices();
			$priceValue = PHP_INT_MAX;
			if ($price->count()) {
				$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
			}
			$this->data[$car->getOffset()]['price_tmp'] = $priceValue;
		}
		foreach ($sort as $sk => $sv) {
			$dir = $sv;
			if (isset($sortFuncs[$sk])) usort($this->data, $sortFuncs[$sk]);
		}
		return $this;
	}

	public function calcPricesDated($idx, $from, $to, $course, $type, $perDayAdd = 0) {
		$id = $this[$idx]->id;
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $id));
		if ($type) $this->price->filter($this->price->filterExpr()->eq('type', $type));
		$this->price->order('value')->exec();
		$f = new DateTime($from);
		$t = new DateTime($to);

		$int = $f->diff($t, true)->days;
		if ($int < 1) $int = 1;
		$days = array();
		for ($i = 0; $i < $int; $i++) $days[$i] = 0;

		foreach($this->price as $price) {
			$pf = new DateTime($price->start);
			$pt = new DateTime($price->end);
			if (($pf > $t && $pt > $t && !$price->flags->check(PriceModel::START_INVALID)) || ($pf < $f && $pt < $f && !$price->flags->check(PriceModel::END_INVALID))) continue;
			if ($price->flags->check(PriceModel::START_INVALID)) $if = 0;
			else if ($pf < $f) $if = 0;
			else {
				$if = $f->diff($pf,true)->days;
			}

			if ($price->flags->check(PriceModel::END_INVALID)) $it = $int;
			else if ($pt > $t) $it = $int;
			else { // $pt <= $t, end index is difference between period beginning and price finish
				$it = $f->diff($pt,true)->days ;
			}
			$value = $price->calcValue($course);
			for($i = $if; $i < $it; $i++) {
				if ($days[$i] == 0 || $days[$i] > $value) $days[$i] = $value;
			}
		}
		$disc = 0;
		if ($int > 29) $disc = $this[$idx]->discount5;
		elseif ($int > 15) $disc = $this[$idx]->discount4;
		elseif ($int > 8) $disc = $this[$idx]->discount3;
		elseif ($int > 6) $disc = $this[$idx]->discount2;
		elseif ($int > 2) $disc = $this[$idx]->discount1;
		$sum = 0;
		for ($i = 0; $i < $int; $i++) $sum += ($days[$i] - $disc + $perDayAdd) / $course;
		return ceil($sum);
	}

	public function getPrices($idx, $type = false) {
		return $this->getPricesId($this[$idx]->id, $type);
	}

	public function getPricesId($id, $type = false) {
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $id));
		if ($type) $this->price->filter($this->price->filterExpr()->eq('type', $type));
		$this->price->filter($this->price->filterExpr()->eq('flags', PriceModel::END_INVALID)->_or()
			->more('end', new DateTime()))->order('value')->exec();
		return $this->price;
	}

	public function getMainImage($idx) {
		$this->image->get()->filter(
			$this->image->filterExpr()->eq('flags', CarImageModel::FLAG_MAIN)->_and()
		->eq('car_id', $this[$idx]->id)
		)->exec();
		if ($this->image->count()) return $this->image[0];
		return null;
	}

	public function getOtherImages($idx) {
		$this->image->get()->filter(
			$this->image->filterExpr()->notEq('flags', CarImageModel::FLAG_MAIN)->_and()
				->eq('car_id', $this[$idx]->id)
		)->exec();
		if ($this->image->count()) return $this->image;
		return array();
	}

	public function getCar($id) {


		$this->get()->filter($this->filterExpr()->eq('flags', self::FLAG_VISIBLE)->_and()->eq(MODEL_ID_FIELD_NAME, $id))
			->exec();
		if (!$this->count()) throw new NotFoundException();

		return $this[0];
	}

	public function filterByField($field, $func) {
		$newdata = array();
		foreach($this->data as $row) {
			if ($func($row[$field])) $newdata[] = $row;
		}
		$this->data = $newdata;
	}
}
