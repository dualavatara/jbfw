<?
require_once "PHPUnit/Extensions/Database/TestCase.php";
require_once 'lib/db.pg.lib.php';

abstract class PHPUnit_Postgres_Database_TestCase extends PHPUnit_Extensions_Database_TestCase {
	// only instantiate pdo once for test clean-up/fixture load
	static private $pdo = null;
	static private $pg = null;
	// only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
	private $conn = null;

	final public function getConnection() {
		if ($this->conn === null) {
			if (self::$pdo == null) {
				self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
			}

			$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
		}

		return $this->conn;
	}

	/**
	 * @return PGDatabase
	 */
	final public function getPostgres() {
		if (self::$pg == null) {
			self::$pg = new PGDatabase($GLOBALS['DB_HOST'],$GLOBALS['DB_PORT'],$GLOBALS['DB_USER'],$GLOBALS['DB_PASSWD'],$GLOBALS['DB_DBNAME'],'utf-8');
		}
		return self::$pg;
	}
	
	
    /**
     * Asserts that result part of JsonRPC response are equal to expected array.
     *
     * @param  array  $expected
     * @param  string $jsonResponse
     * @param  string $message
	 */
	public function assertJsonRPCResult($expected, $jsonResponse, $message = '') {
		$expected = array(
			'jsonrpc' => 2,
			'id' => null,
			'result' => $expected == null ? null : (array)$expected
		);
		$jsonResponse = json_decode($jsonResponse, true); // decode to associative array not ot object
		self::assertEquals($expected, $jsonResponse, $message);
	}
}

?>
