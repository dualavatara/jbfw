<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 1:23
 */

namespace model;

require_once 'model/PriceModel.php';
require_once 'admin/lib/AdminModel.php';

class Price extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \PriceModel($db));
	}
}
