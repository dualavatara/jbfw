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
		$this->field(new CharField('name', Field::ADMIN_LIST|Field::ADMIN_LIST_EDIT, "Название"));
		$this->field(new CharField('sign', Field::ADMIN_LIST, 'Обозначение'));
		$this->field(new RealField('course', Field::ADMIN_LIST, 'Курс'));
	}
}
