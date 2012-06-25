<?php
namespace model;

require_once 'model/UrlAliasesModel.php';
require_once 'admin/lib/AdminModel.php';

class UrlAliases extends \AdminModel {
	/**
	* @param IDatabase $db
	*/
	public function __construct(\IDatabase $db) {
		parent::__construct(new \UrlAliasesModel($db));
		$this->fields['id'] = new \DefaultAdminField('id','Id', true);
		$this->fields['alias'] = new \DefaultAdminField('alias','Alias', true);
		$this->fields['url'] = new \DefaultAdminField('url','Url', true);
	}
}
