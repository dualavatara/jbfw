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
}
