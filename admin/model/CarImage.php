<?php
namespace model;

require_once 'model/CarImageModel.php';
require_once 'admin/lib/AdminModel.php';

class CarImage extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CarImageModel($db), '\ParentChildParams');
		$this->fields['id'] = new \DefaultAdminField('id', 'Id', true, true, true);
		$this->fields['image'] = new \ImageAdminField('image', 'Изображение', true);
		$this->fields['thumbnail'] = new \ImageAdminField('thumbnail', 'Предпросмотр 320x200', true);
		$this->fields['thumbnail50'] = new \ImageThumbnailAdminField('thumbnail50', 'image', 50, 50, 'Предпросмотр 50x50', true);
		$this->fields['thumbnail125'] = new \ImageThumbnailAdminField('thumbnail125', 'image', 125, 125, 'Предпросмотр 125x125', true);
		$this->fields['thumbnail200'] = new \ImageThumbnailAdminField('thumbnail200', 'image', 200, 200, 'Предпросмотр 200x200', true);

		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['car_id'] = new \BackrefAdminField('car_id', 'ID автомобиля', $_SESSION['urlparams']['parent_id'], false);
	}

	public function onSave($form) {
		$result = '';
		if ($form['flags'] & \CarImageModel::FLAG_MAIN )
			$this->getModel()->db->getQueryArray('UPDATE `car_image` SET `flags` = `flags` & (0xffff ^ '. \CarImageModel::FLAG_MAIN .') WHERE `car_id` = \''.$form['car_id'].'\';', false, $result);
	}
}
