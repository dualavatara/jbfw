<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 4:04
 */

require_once 'lib/model.lib.php';

class CurrencyModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('currency', $db);
		$this->field('name', new CharField('name'));
		$this->field('sign', new CharField('sign'));
		$this->field('course', new RealField('course'));
	}

	/**
	 * Select all currency rows from database
	 */
	public function getAll() {
		$this->get()->all()->exec();
	}

	/**
	 * Adds new currency record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function add($name, $sign, $course) {
		$this->clear();
		$this[0] = array('name' => $name, 'sign' =>$sign, 'course' => $course);
		$this->insert()->exec();
	}

	/**
	 * Selects currency by id
	 * @param $id
	 * @return mixed	array if found, otherwise false
	 */
	public function getById($id) {
		$this->get($id)->exec();
		if ($this->count()) return $this[0]->all();
		return false;
	}

	/**
	 * Saves single elemet from form array as array('field' => 'value', ...)
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
	 * Deletes record with given id
	 * @param $id
	 */
	public function delById($id) {
		$this->get($id)->delete()->exec();
	}
}
