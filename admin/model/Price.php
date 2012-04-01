<?php
namespace model;

require_once 'model/PriceModel.php';
require_once 'admin/lib/AdminModel.php';

class Price extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \PriceModel($db), '\ClassObjectChildParams');
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true);
		$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true, false, false, 10);
		$this->fields['start'] = new \DateTimeAdminField('start','Начало', true);
		$this->fields['end'] = new \DateTimeAdminField('end','Конец', true);
		$this->fields['currency_id'] = new \SelectAdminField('currency_id','Валюта', 'getCurrencies', true);
		$this->fields['value'] = new \FloatAdminField('value','Значение', true);
		$this->fields['week_disc'] = new \FloatAdminField('week_disc','Скидка при заказе недели, %', true);
		$this->fields['month_disc'] = new \FloatAdminField('month_disc','Скидка при заказе месяца, %', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);

		$this->fields['class_id'] = new \BackrefAdminField('class_id','Class ID', $_SESSION['urlparams']['class_id'], true);
		$this->fields['object_id'] = new \BackrefAdminField('object_id','Object ID', $_SESSION['urlparams']['object_id'], true);
	}
}
