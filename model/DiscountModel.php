<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 5:06 AM
 */

require_once 'lib/model.lib.php';

class DiscountModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('discount', $db);
		$this->field(new CharField('description'));
		$this->field(new RealField('percent'));
	}
}
