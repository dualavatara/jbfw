<?php
namespace model;

require_once 'model/ArticleModel.php';
require_once 'admin/lib/AdminModel.php';

class Article extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \ArticleModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		$this->fields['link'] = new \FieldInfoAdminField('/article/{id}', 'Ссылка для навигации', true);
		$this->fields['name'] = new \DefaultAdminField('name','Заголовок', true, true);
		$this->fields['photo'] = new \ImageAdminField('photo','Фото', false);
		$this->fields['photo_preview'] = new \ImageThumbnailAdminField(
			'photo_preview',
			'photo',
			225,
			0,
			'Превью фото',
			false);
		$this->fields['content'] = new \TextAdminField('content','Содержание', false);
		$this->fields['content_short'] = new \TextAdminField('content_short','Короткое содержание', false);
		$this->fields['type'] = new \SelectAdminField('type','Тип', 'getTypes', true);
		$this->fields['created'] = new \DateTimeAdminField('created','Дата создания', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Порядок', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);

		$this->fields['images'] = new \RefAdminField('images','Картинки', new \ParentChildParams(array('parent_field' => 'article_id')), true);
		$this->fields['images']->class = 'ArticleImage';
		$this->fields['images']->fromRoute = 'article	_list';
	}
}
