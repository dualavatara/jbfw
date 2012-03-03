<?php
namespace model;

require_once 'model/BeachModel.php';
require_once 'admin/lib/AdminModel.php';

class Beach extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \BeachModel($db));
	}
}
