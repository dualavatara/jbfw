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
	private $lastOID = null;

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

	public function getQueryArray($sql, $async, &$result)  {
		$result = array();
		if (!($dbResult  = $this->execSQL($sql))) return false;
		while (is_array($row = pg_fetch_assoc($dbResult))) {
			$result[] = $row;
		}
		$this->lastOID = pg_getlastoid($dbResult);
		pg_free_result($dbResult);
		return true;
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
	
	public function escape($data) {
		if (!$this->dbh && !$this->connect()) return false;
		return pg_escape_string($this->dbh, $data);
	}
	
	public function getLastQuery() { return $this->lastQuery; }
	
	public function quot($value, $valquot = false) { 
		if ($valquot) return '\'' . $value . '\''; 
		else return '"' . $value . '"';
	}

	/**
	 *
	 */
	public function getLastInsertId() {
		return $this->lastOID;
	}
}

?>