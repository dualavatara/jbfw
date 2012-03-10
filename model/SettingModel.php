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
	const FAX			= 3;
	const SKYPE			= 4;
	const ADDRESS		= 7;
	const EMAIL			= 9;
	const DESCRIPTION	= 10;
	const TITLE			= 11;
	const SEOTEXT		= 13;
	const KEYWORDS		= 14;
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
	public function getFax() { return $this->atId(SettingModel::FAX)->value; }
	public function getSkype() { return $this->atId(SettingModel::SKYPE)->value; }
	public function getAddress() { return $this->atId(SettingModel::ADDRESS)->value; }
	public function getEmail() { return $this->atId(SettingModel::EMAIL)->value; }
	public function getSEOText() { return $this->atId(SettingModel::SEOTEXT)->value; }
	public function getTitle() { return $this->atId(SettingModel::TITLE)->value; }
	public function getDescription() { return $this->atId(SettingModel::DESCRIPTION)->value; }
	public function getKeywords() { return $this->atId(SettingModel::KEYWORDS)->value; }
}
