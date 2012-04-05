<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 1:02 AM
 */

require_once 'lib/model.lib.php';

class CarTypeModel extends Model {
	public function __construct(IDatabase $db) {
		parent::__construct('car_type', $db);
		$this->field(new CharField('name'));
	}
}
