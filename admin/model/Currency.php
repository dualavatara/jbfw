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
		$this->fields[] = new \FloatAdminField('course','Курс, EURO', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
	}

	public function onSave($form) {
		$result = '';
		if ($form['flags'] & \CurrencyModel::FLAG_DEFAULT )
			$this->getModel()->db->getQueryArray('UPDATE `currency` SET `flags` = `flags` & (0xffff ^ '. \CurrencyModel::FLAG_DEFAULT .');', false, $result);
	}
}
