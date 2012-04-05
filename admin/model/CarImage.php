<?php
namespace model;

require_once 'model/CarImageModel.php';
require_once 'admin/lib/AdminModel.php';

class CarImage extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \RealtyImageModel($db), '\ParentChildParams');
		$this->fields['id'] = new \DefaultAdminField('id', 'Id', true, true, true);
		$this->fields['thumbnail'] = new \ImageAdminField('thumbnail', 'Предпросмотр', true);
		$this->fields['image'] = new \ImageAdminField('image', 'Изображение', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['realty_id'] = new \BackrefAdminField('car_id', 'ID автомобиля', $_SESSION['urlparams']['parent_id'], false);
	}
}
