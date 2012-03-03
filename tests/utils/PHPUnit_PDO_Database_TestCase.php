<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 16:10
 */

require_once "PHPUnit/Extensions/Database/TestCase.php";
require_once "lib/PDODatabase.php";

abstract class PHPUnit_PDO_Database_TestCase extends PHPUnit_Extensions_Database_TestCase {
	/**
	 * only instantiate pdo once for test clean-up/fixture load
	 * @var PDODatabase
	 */
	static private $pdo = null;

	static private $db = null;
	/**
	 * only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
	 * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	private $conn = null;

	final public function getConnection() {
		if ($this->conn === null) {
			if (self::$pdo == null) {
				self::$pdo = new PDODatabase($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], "utf8");
				self::$pdo = self::$pdo->getDbh();
			}

			$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
		}

		return $this->conn;
	}

	/**
	 * @return \PDODatabase
	 */
	public static function getDb() {
		if (self::$db == null) {
			self::$db = new PDODatabase($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], "utf8");
		}
		return self::$db;
	}
}
