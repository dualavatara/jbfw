<?
interface IDatabase {
    public function execSQL($sql, $async, $additional = "");
    public function getQueryRow($sql, $async, &$result);
    public function getResultRow($result);
    public function getQueryArray($sql, $async, &$result);
    public function getQueryVal($sql, $async, &$result);
    public function getQueryCol($sql, $async, &$result);
    public function affectedRows($result);
    public function insertId($tableName= '', $result);
    
    //service functions for engine-specific sql tokens
    public function getLimitStr($offset, $count);
    public function getReturningStr();
    public function escape($data);
    public function getLastQuery();
    public function quot($value, $valquot = false);
}
?>