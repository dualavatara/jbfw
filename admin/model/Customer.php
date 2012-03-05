<?php
namespace model;

require_once 'model/CustomerModel.php';
require_once 'admin/lib/AdminModel.php';

class Customer extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CustomerModel($db));
	}
}
