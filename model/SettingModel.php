<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 10:56 PM
 */

require_once 'lib/model.lib.php';

class SettingModel extends Model {
	const PHONE_1		= 1;
	const PHONE_2		= 2;
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('settings', $db);

		$this->field(new CharField('name'));
		$this->field(new Charfield('value'));
	}

	public function atId($id) {
		foreach($this as $w) if ($w->id == $id) return $w;
		return null;
	}
	public function getPhone1() { return $this->atId(SettingModel::PHONE_1)->value; }
	public function getPhone2() { return $this->atId(SettingModel::PHONE_2)->value; }
}
