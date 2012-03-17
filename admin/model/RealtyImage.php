<?php
namespace model;

require_once 'model/RealtyImageModel.php';
require_once 'admin/lib/AdminModel.php';

class RealtyImage extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \RealtyImageModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['thumbnail'] = new \ImageAdminField('thumbnail','Предпросмотр', true);
		$this->fields['image'] = new \ImageAdminField('image','Изображение', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['realty_id'] = new \BackrefAdminField('realty_id','ID объекта недвижимости', false);
	}
}
