<?php

require_once 'lib/model.lib.php';

class AdminUserModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('admin_users', $db);
		$this->field('login', new CharField('login'));
		$this->field('password', new CharField('password'));
		$this->field('name', new CharField('name'));
		$this->field('created', new DateTimeWithTZField('created'));
	}
}