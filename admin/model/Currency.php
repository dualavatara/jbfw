<?php
namespace model;

require_once 'model/CurrencyModel.php';
require_once 'admin/lib/AdminModel.php';

class Currency extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CurrencyModel($db));
		$this->fields[] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields[] = new \DefaultAdminField('name','Название', true, true);
		$this->fields[] = new \DefaultAdminField('sign','Обозначение', true);
		$this->fields[] = new \FloatAdminField('course','Курс', true);
	}
}
