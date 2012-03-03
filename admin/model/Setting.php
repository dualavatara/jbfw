<?php
namespace model;

require_once 'model/SettingModel.php';
require_once 'admin/lib/AdminModel.php';

class Setting extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \SettingModel($db));
	}
}
