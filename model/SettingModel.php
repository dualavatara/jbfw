<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 10:56 PM
 */

require_once 'lib/model.lib.php';
require_once 'lib/singletone.lib.php';

class Settings extends Singletone {
	/**
	 * @var SettingModel
	 */
	private $model;

	/**
	 * @param \SettingModel $model
	 */
	public function set(SettingModel $model) {
		$this->model = $model;
		$model->get()->all()->exec();
	}

	/**
	 * @return SettingModel
	 */
	public function get() {
		return $this->model;
	}
}
/**
 *
 */
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

	/**
	 * @param $id
	 * @return null
	 */
	public function atId($id) {
		foreach($this as $w) if ($w->id == $id) return $w;
		return null;
	}

	/**
	 * Coverage of getters is unnecessary
	 * @codeCoverageIgnore
	 * @return mixed
	 */
	public function getPhone1() { return $this->atId(SettingModel::PHONE_1)->value; }

	//@codeCoverageIgnoreStart

	/**
	 * @return mixed
	 */
	public function getPhone2() { return $this->atId(SettingModel::PHONE_2)->value; }

	/**
	 * @return mixed
	 */
	public function getFax() { return $this->atId(SettingModel::FAX)->value; }

	/**
	 * @return mixed
	 */
	public function getSkype() { return $this->atId(SettingModel::SKYPE)->value; }

	/**
	 * @return mixed
	 */
	public function getAddress() { return $this->atId(SettingModel::ADDRESS)->value; }

	/**
	 * @return mixed
	 */
	public function getEmail() { return $this->atId(SettingModel::EMAIL)->value; }

	/**
	 * @return mixed
	 */
	public function getSEOText() { return $this->atId(SettingModel::SEOTEXT)->value; }

	/**
	 * @return mixed
	 */
	public function getTitle() { return $this->atId(SettingModel::TITLE)->value; }

	/**
	 * @return mixed
	 */
	public function getDescription() { return $this->atId(SettingModel::DESCRIPTION)->value; }

	/**
	 * @return mixed
	 */
	public function getKeywords() { return $this->atId(SettingModel::KEYWORDS)->value; }

	//@codeCoverageIgnoreEnd
}
