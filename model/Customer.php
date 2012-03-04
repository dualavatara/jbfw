<?php
/**
 * User: dualavatara
 * Date: 3/4/12
 * Time: 8:09 AM
 */

require_once 'lib/model.lib.php';

class Customer extends Model {
	public function __construct(IDatabase $db) {
		parent::__construct('customer', $db);

		$this->field(new CharField('email'));
		$this->field(new CharField('name'));
		$this->field(new CharField('phone_msk'));
		$this->field(new CharField('phone_local'));
		$this->field(new CharField('address'));
		$this->field(new CharField('country'));
		$this->field(new CharField('skype'));
		$this->field(new CharField('icq'));
		$this->field(new CharField('admin_note'));
	}
}
