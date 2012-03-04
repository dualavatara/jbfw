<?php

require_once 'lib/model.lib.php';

class AdminUserModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('admin_users', $db);
		$this->field(new CharField('login'));
		$this->field(new CharField('password'));
		$this->field(new CharField('name'));
		$this->field(new DateTimeWithTZField('created'));
	}
}