<?php
namespace model;

require_once 'lib/db.lib.php';
require_once 'model/DiscountModel.php';
require_once 'admin/lib/AdminModel.php';

class Discount extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \DiscountModel($db));
	}
}
