<?
interface IDatabase {

	public function getQueryArray($sql, $async, &$result);

	//service functions for engine-specific sql tokens
	public function escape($data);

	public function getLastQuery();

	public function quot($value, $valquot = false);
}

?>