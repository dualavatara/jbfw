<?php

require_once('lib/db.lib.php');
require_once('lib/logger.lib.php');

/**
 * Created by JetBrains PhpStorm.
 * User: zhukov
 * Date: 27.02.12
 * Time: 0:24.
 */
class PDODatabase implements IDatabase {
	/**
	 * @var PDO
	 */
	private $dbh;

	/**
	 * @var string
	 */
	private $lastQuery;

	public function getQueryArray($sql, $async, &$result) {
		$statement = $this->dbh->prepare($sql);
		if (!$statement) {
			Logger::obj()->error("PDO can not prepare SQL:" . $sql);
		} else {
			if ($statement->execute()) {
				$result = $statement->fetchAll();
				return true;
			} else {
				$err = $statement->errorInfo();
				$log = "SQL error " . $err[1] . ": " . $err[2];
				Logger::obj()->error($log);
			}
		}
		return false;
	}

	public function escape($data) {
		return addslashes($data);
	}

	public function getLastQuery() {
		return $this->lastQuery;
	}

	public function quot($value, $valquot = false) {
		if ($valquot) return $this->dbh->quote($value);
		return $value;
	}

	function __construct($dsn, $username, $password, $charset) {
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $charset,
		);

		$this->dbh = new PDO($dsn, $username, $password, $options);
	}

	/**
	 *
	 */
	public function getLastInsertId() {
		return $this->dbh->lastInsertId();
	}
}
