<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:24
 */

require_once('lib/model.lib.php');

class PriceModel extends Model{

	const START_VALID	= 0x0001;
	const END_VALID		= 0x0002;

	public function __construct(IDatabase $db) {
		parent::__construct("price", $db);

		$this->field("start", new DateTimeWithTZField("start"));
		$this->field("end", new DateTimeWithTZField("end"));
		$this->field("currency_id", new IntField("currency_id"));
		$this->field("value", new RealField("value"));

		$this->field("flags", new IntField("flags"));
	}

	/**
	 * Select all object`s rows from database
	 */
	public function getAll() {
		$this->get()->all()->exec();
	}

	/**
	 * Adds new object record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function addFromForm($form) {
		$this->clear();
		unset($form['id']);
		$this[0] = $form;
		$this->insert()->exec();
	}

	/**
	 * Selects object by id
	 * @param $id
	 * @return mixed	array if found, otherwise false
	 */
	public function getById($id) {
		$this->get($id)->exec();
		if ($this->count()) return $this[0]->all();
		return false;
	}

	/**
	 * Saves single object from form array as array('field' => 'value', ...)
	 * $form['id'] is required
	 * @param array $form
	 */
	public function saveFromForm($form) {
		if (isset($form['id'])){
			$this->clear();
			$this[0] = $form;
			$this->update()->exec();
		}
	}

	/**
	 * Deletes object by id
	 * @param $id
	 */
	public function delById($id) {
		$this->get($id)->delete()->exec();
	}
}
