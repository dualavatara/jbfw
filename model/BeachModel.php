<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 8:54 PM
 */

require_once 'lib/model.lib.php';

class BeachModel extends Model {
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('beach', $db);
		$this->field('name', new CharField('name'));
		$this->field('link', new CharField('link'));
	}
}
