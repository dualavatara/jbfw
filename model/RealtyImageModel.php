<?php
/**
 * User: dualavatara
 * Date: 3/18/12
 * Time: 12:15 AM
 */

require_once 'lib/model.lib.php';

class RealtyImageModel extends  Model {
	public function __construct(IDatabase $db) {
		parent::__construct('realty_image', $db);

		$this->field(new CharField('thumbnail'));
		$this->field(new CharField('image'));
		$this->field(new IntField('realty_id'));
	}

}
