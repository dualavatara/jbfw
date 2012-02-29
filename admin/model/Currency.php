<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 1:23
 */

namespace model;

require_once 'model/CurrencyModel.php';
require_once 'admin/lib/AdminModel.php';

class Currency extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \CurrencyModel($db));
	}
}
