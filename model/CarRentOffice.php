<?php
/**
 * User: dualavatara
 * Date: 3/4/12
 * Time: 7:45 AM
 */

require_once 'lib/model.lib.php';

class CarRentOffice extends Model {
	public function __construct(IDatabase $db) {
		parent::__construct('car_rent_office', $db);

		$this->field(new CharField('name'));
		$this->field(new CharField('description'));
		$this->field(new RealField('percent'));
		$this->field(new IntField('customer_id'));
		$this->field(new RatingField('customer_id'));
	}
}
