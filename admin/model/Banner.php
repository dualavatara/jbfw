<?php
namespace model;

require_once 'model/BannerModel.php';
require_once 'admin/lib/AdminModel.php';

class Banner extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \BannerModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true, true);
		$this->fields['image'] = new \ImageAdminField('image','Изображение', true);
		$this->fields['link'] = new \DefaultAdminField('link','Ссылка', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Ord', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
	}
}
