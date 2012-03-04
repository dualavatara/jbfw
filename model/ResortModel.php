<?php
/**
 * User: dualavatara
 * Date: 3/3/12
 * Time: 11:56 PM
 */

require_once 'lib/model.lib.php';

/**
 *
 */
class ResortModel extends Model {

	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('resort', $db);

		$this->field(new CharField('name'));
		$this->field(new CharField('link'));
		$this->field(new CharField('gmaplink'));
	}

}
