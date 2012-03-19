<?php
/**
 * User: dualavatara
 * Date: 3/17/12
 * Time: 8:49 PM
 */

require_once 'lib/model.lib.php';
require_once 'model/RealtyImageModel.php';
require_once 'model/ResortModel.php';

class RealtyModel extends Model {
	const TYPE_VILLA	= 1;
	const TYPE_HOTEL	= 2;

	const FLAG_VISIBLE		= 0x0001;

	private $imgModel;
	private $resort;

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
		$this->field(new IntField('resort_id'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));

		$this->imgModel = new RealtyImageModel($db);
		$this->resort = new ResortModel($db);
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
		);
	}

	public function loadDependecies() {
		$this->imgModel->get()->filter($this->imgModel->filterExpr()->eq('realty_id', $this->id))->exec();
		$this->resort->get()->filter($this->imgModel->filterExpr()->eq('id', $this->resort_id))->exec();
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
}
