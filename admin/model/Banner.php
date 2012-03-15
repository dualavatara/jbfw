<?php
namespace model;

require_once 'model/BannerModel.php';
require_once 'admin/lib/AdminModel.php';

class Banner extends \AdminModel {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(\IDatabase $db) {
		parent::__construct(new \BannerModel($db));
	}
}
