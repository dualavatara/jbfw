<?php
/**
 * User: dualavatara
 * Date: 6/25/12
 * Time: 11:57 AM
 */

require_once 'lib/model.lib.php';
require_once 'lib/singletone.lib.php';

class UrlAliases extends Singletone {
	/**
	 * @var UrlAliasesModel
	 */
	private $model;

	/**
	 * @param \UrlAliasesModel $model
	 */
	public function set(UrlAliasesModel $model) {
		$this->model = $model;
		$model->get()->all()->exec();
	}

	/**
	 * @return UrlAliasesModel
	 */
	public function get() {
		return $this->model;
	}

	/**
	 * @static
	 * @return UrlAliases
	 */
	public static function obj() {
		return parent::obj();
	}
}

class UrlAliasesModel extends Model {
	public function __construct(IDatabase $db) {
		parent::__construct('url_aliases', $db);

		$this->field(new CharField('alias'));
		$this->field(new Charfield('url'/*,Field::STRIP_SLASHES*/));
	}
}
