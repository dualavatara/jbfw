<?
require_once('lib/db.lib.php');
require_once('lib/logger.lib.php');

define('FIELD_QUOTE', '"');

class PGDatabase implements IDatabase {
	var $dbh;
	var $dbHost = '';
	var $dbPort = '';
	var $dbName = '';
	var $dbUser = '';
	var $dbPass = '';
	
	var $dieOnError = false;
	var $error = '';
	var $charSet = false;
	var $noPconnect = false;
	
	private $lastQuery = '';

	private function connect() {
		if ($this->dbh) return true;

		// Persistent or not persistent connection
		if (!$this->noPconnect) {
			$this->dbh = pg_connect($this->connectStr);
		} else {
			$this->dbh = pg_pconnect($this->connectStr);
		}
		if (!$this->dbh) {
			$this->error = sprintf("Can't connect to host: %s:%d", $this->dbHost, $this->dbPort);
			die(date('[Y-m-d H:i:s] ').$this->error."\n");
		}
		return true;
	}

	public function PGDatabase($dbHost, $dbPort, $dbUser, $dbPass, $dbName, $charSet=false, $noPconnect=false) {
		$this->dbHost = $dbHost;
		$this->dbPort = $dbPort;
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
		$this->charSet = $charSet;
		$this->noPconnect = $noPconnect;
		$this->connectStr = sprintf("host=%s port=%s dbname=%s user=%s password=%s options='--client_encoding=%s'", $dbHost, $dbPort, $dbName, $dbUser, $dbPass, $this->charSet);
		$this->init();
	}

	private function close() {
		pg_close($this->dbh);
	}

	private function init() {
	}

	// get last error
	public function error() {
		return $this->error;
	}

	// execute sql string
	// add ~ "and visible=0 limit 100,10"
	public function execSQL($sql, $async = false, $add = '') {
		if (!$this->dbh && !$this->connect()) return false;
		if ($add) $sql .= ' '.$add;
		$sql = '/* '.$_SERVER['PHP_SELF'].' */ '.$sql;
		$this->lastQuery = $sql;
		if (!(@$result = pg_query($this->dbh, $sql))) {
			$this->error = pg_last_error($this->dbh);
			$log = $this->error ."\n";
			
			ob_start();
			if (defined('SQL_BACKTRACE') && SQL_BACKTRACE) debug_print_backtrace();
			
			$log .= ob_get_clean() . "\n" . 'SQL request:' . $sql."\n";

			Logger::obj()->error($log);

			if ($this->dieOnError) throw new Exception('<pre>'.$log.'</pre>');//die('<pre>'.$log.'</pre>');
		}
		return $result;
	}

	public function getQueryArray($sql, $async = false, &$result)  {
		$result = array();
		if (!($dbResult = $this->execSQL($sql))) return false;
		while (is_array($row = pg_fetch_assoc($dbResult))) {
			$result[] = $row;
		}
		pg_free_result($dbResult);
		return true;
	}

	public function getQueryRow($sql, $async = false, &$result)  {
		$result = array();
		if (!($dbResult = $this->execSQL($sql))) return false;
		if (is_array($row = pg_fetch_assoc($dbResult))) $result = $row;
		pg_free_result($dbResult);
		return true;
	}
	
	public function getResultRow($result)  {
		$row = pg_fetch_assoc($result);
		pg_free_result($result);
		return $row;
	}

	public function getQueryVal($sql, $async = false, &$result)  {
		if (!($dbResult = $this->execSQL($sql))) return false;
		$row = pg_fetch_assoc($dbResult);
		pg_free_result($dbResult);
		if (!$row) return false;
		$row = array_values($row);
		$result = $row[0];
		return true;
	}
	
	public function getQueryCol($sql, $async = false, &$result)  {
		$result = array();
		if (!($dbResult = $this->execSQL($sql))) return false;
		while (is_array($row = pg_fetch_array($dbResult))) $result[] = $row[0];
		pg_free_result($dbResult);
		return true;
	}

	function callFunc ($name = '', $args=array()) {
		$sql = '';
	}

	public function execQuery($sql)  {
		if (!($dbResult = $this->execSQL($sql))) return false;
		return $this->affectedRows($dbResult);
	}

	public function insertId($tableName, $result) {
		global $tableInfo;
		if ($tableInfo['noInsertId']) return 0;
		if (!$result) return 0;
		$sql = sprintf("select currval('%s_id_seq')", $tableName);
		$this->getQueryRow($sql, $dbResult);
		return intval($dbResult['currval']);
	}

	public function affectedRows($result) {
		return $result ? pg_affected_rows($result) : 0;
	}

	public function getLimitStr($limit, $offset) {
		$ret = ' ';
		$ret .= 'LIMIT '.$limit; 
		if ($offset) $ret .= ' OFFSET '.$offset; 
		return $ret;
	}
	
	public function getLikeStr () {
		return ' ILIKE ';
	}
	
	// gets primary key
	public function getPrimary ($tableName = '') {
		$sql = sprintf("SELECT c2.relname, a.attname FROM pg_class c, pg_class c2, pg_index i, pg_attribute a WHERE 
			c.relname = '%s' AND c.oid = i.indrelid AND i.indexrelid = c2.oid
			AND i.indisprimary AND i.indisunique
			AND a.attrelid=c2.oid AND a.attnum>0;", $tableName);
		$this->getQueryRow($sql, $result);
		return $result['attname'];
	}
	
	public function getReturningStr() {
		return ' RETURNING * ';
	}
	
	public function escape($data) {
		if (!$this->dbh && !$this->connect()) return false;
		return pg_escape_string($this->dbh, $data);
	}
	
	public function getLastQuery() { return $this->lastQuery; }
	
	public function quot($value, $valquot = false) { 
		if ($valquot) return '\'' . $value . '\''; 
		else return '"' . $value . '"';
	}
}

?>