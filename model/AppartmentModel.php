<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 2:51 AM
 */
require_once 'lib/model.lib.php';
require_once 'model/PriceModel.php';
require_once 'model/AppartmentTypeModel.php';

class AppartmentModel extends Model {
	const FLAG_VISIBLE		= 0x0001;
	const FLAG_DISCOUNT = 0x0004;

	private $price;
	private $type;

	public function __construct(IDatabase $db) {
		parent::__construct('appartment', $db);
		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new CharField('features'));
		$this->field(new IntField('type'));
		$this->field(new IntField('realty_id'));
		$this->field(new IntField('rooms'));
		$this->field(new IntField('adults'));
		$this->field(new IntField('kids'));
		$this->field(new IntField('bedrooms'));
		$this->field(new IntField('floor'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));

		$this->price = new PriceModel($db);
		$this->type = new AppartmentTypeModel($db);
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
			self::FLAG_DISCOUNT => 'Скидка',
		);
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

	public function filterType($typeId) {
		$newdata = array();
		foreach($this->data as $row) {
			if ($row['type'] == $typeId) $newdata[] = $row;
		}
		$this->data = $newdata;
	}

	public function filterPricesDate($from, $to) {
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $this->id))->exec();
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

	public function filterByField($field, $func) {
		$newdata = array();
		foreach($this->data as $row) {
			if ($func($row[$field])) $newdata[] = $row;
		}
		$this->data = $newdata;
	}

	public function filterByPrice($type, $func) {
		$this->price->get()->filter($this->price->filterExpr()->eq('class_id', $this->price->getClassId($this))->_and()
			->eq('object_id', $this->id))->exec();
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
}
