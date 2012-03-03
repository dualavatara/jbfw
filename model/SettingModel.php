<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 10:56 PM
 */

require_once 'lib/model.lib.php';

class SettingModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('settings', $db);

		$this->field('name', new CharField('name'));
		$this->field('value', new Charfield('value'));
	}
}
