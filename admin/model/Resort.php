<?php
namespace model;

require_once 'model/ResortModel.php';
require_once 'admin/lib/AdminModel.php';

class Resort extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \ResortModel($db));
	}
}
