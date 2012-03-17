<?php
/**
 * User: dualavatara
 * Date: 3/17/12
 * Time: 4:24 AM
 */

require_once 'lib/db.lib.php';

class DummyDatabase implements IDatabase{
	/**
	 * @param string $sql SQL statement to execute
	 * @param bool $async
	 * @param mixed $result reference to store result
	 */
	public function getQueryArray($sql, $async, &$result) {
	}

	/**
	 *
	 */
	public function getLastInsertId() {
	}

	/**
	 * @param $data
	 */
	public function escape($data) {
	}

	/**
	 *
	 */
	public function getLastQuery() {
	}

	/**
	 * @param      $value
	 * @param bool $valquot
	 */
	public function quot($value, $valquot = false) {
	}

}
