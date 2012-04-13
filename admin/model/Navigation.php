<?php
namespace model;

require_once 'model/NavigationModel.php';
require_once 'admin/lib/AdminModel.php';

class Navigation extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \NavigationModel($db), '\ParentChildParams');
		$this->fields['id'] = new \DefaultAdminField('id','Id', true, true, true);
		//$this->fields['parent_id'] = new \DefaultAdminField('parent_id','Parent_id', false);
		$this->fields['name'] = new \DefaultAdminField('name','Текст', true, true);
		$this->fields['link'] = new \DefaultAdminField('link','Ссылка', true);
		$this->fields['flags'] = new \FlagsAdminField('flags','Флаги', true);
		$this->fields['ord'] = new \DefaultAdminField('ord','Порядок', true);

		$this->fields['parent_id'] = new \BackrefAdminField('parent_id', 'ID объекта недвижимости', $_SESSION['urlparams']['parent_id'], false);
		$this->fields['childs'] = new \RefAdminField('childs','Подчиненные', new \ParentChildParams(array('parent_field' => 'parent_id')), true);
		$this->fields['childs']->class = 'Navigation';
		$this->fields['childs']->fromRoute = 'navigation_list';
	}
}
