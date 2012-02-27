<?
/**
 *
 */
interface IDatabase {

	/**
	 * @abstract
	 * @param string $sql SQL statement to execute
	 * @param bool $async
	 * @param mixed $result reference to store result
	 */
	public function getQueryArray($sql, $async, &$result);

	/**
	 * @abstract
	 *
	 */
	public function getLastInsertId();

	//service functions for engine-specific sql tokens
	/**
	 * @abstract
	 * @param $data
	 */
	public function escape($data);

	/**
	 * @abstract
	 *
	 */
	public function getLastQuery();

	/**
	 * @abstract
	 * @param      $value
	 * @param bool $valquot
	 */
	public function quot($value, $valquot = false);
}

?>