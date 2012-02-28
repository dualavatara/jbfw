<?php

require_once 'tests/utils/PHPUnit_PDO_Database_TestCase.php';
require_once 'tests/utils/PHPUnit_ArrayDataSet.php';
require_once 'lib/PDODatabase.php';
require_once 'model/CurrencyModel.php';

/**
 * Test class for CurrencyModel.
 * Generated by PHPUnit on 2012-02-28 at 05:07:54.
 */
class CurrencyModelTest extends PHPUnit_PDO_Database_TestCase {
	/**
	 * @var CurrencyModel
	 */
	protected $object;

	/**
	 * Returns the test dataset.
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet() {
		return new PHPUnit_ArrayDataSet(array(
			'currency' => array(
				array('id' => 1, 'name' => 'Рубль', 'sign' => 'RUB', 'course' => '1'),
				array('id' => 2, 'name' => 'Доллар', 'sign' => 'USD', 'course' => '31.2'),
				array('id' => 3, 'name' => 'Евро', 'sign' => 'EUR', 'course' => '39.86'),
			)
		));
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new CurrencyModel(new PDODatabase($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], 'utf8'));
		parent::setUp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers CurrencyModel::__construct
	 */
	public function testConstruct() {
		$this->assertInstanceOf("CharField", $this->object->getField("name"));
		$this->assertInstanceOf("CharField", $this->object->getField("sign"));
		$this->assertInstanceOf("RealField", $this->object->getField("course"));
	}

	/**
	 * @covers CurrencyModel::getAll
	 */
	public function testGetAll() {
//		$this->object->get()->all()->exec();
		$this->object->getAll();
		$this->assertEquals(array(
			array('id' => 1, 'name' => 'Рубль', 'sign' => 'RUB', 'course' => '1'),
			array('id' => 2, 'name' => 'Доллар', 'sign' => 'USD', 'course' => '31.2'),
			array('id' => 3, 'name' => 'Евро', 'sign' => 'EUR', 'course' => '39.86'),
		), $this->object->data);
	}

	/**
	 * @covers CurrencyModel::add
	 */
	public function testAdd() {
		$this->object->add('Йена','YEN',0.4032);
		$queryTable = $this->getConnection()->createQueryTable(
			'currency', 'SELECT name, sign, course FROM currency'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'currency' => array(
				array('name' => 'Рубль', 'sign' => 'RUB', 'course' => '1'),
				array('name' => 'Доллар', 'sign' => 'USD', 'course' => '31.2'),
				array('name' => 'Евро', 'sign' => 'EUR', 'course' => '39.86'),
				array('name' => 'Йена', 'sign' => 'YEN', 'course' => '0.4032'),
			)
		));
		$expectedTable = $ds->getTable("currency");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}

	/**
	 * @covers CurrencyModel::getById
	 */
	public function testGetById() {
		$this->assertEquals(
			array('id' => 2, 'name' => 'Доллар', 'sign' => 'USD', 'course' => '31.2'),
			$this->object->getById(2));

		$this->assertFalse($this->object->getById(234));
	}

	/**
	 * @covers CurrencyModel::saveFromForm
	 */
	public function testSaveFromForm() {
		$form = array('id' => 2, 'name' => 'Доллар', 'sign' => 'USD', 'course' => '28.6');
		$this->object->saveFromForm($form);
		$queryTable = $this->getConnection()->createQueryTable(
			'currency', 'SELECT name, sign, course FROM currency'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'currency' => array(
				array('name' => 'Рубль', 'sign' => 'RUB', 'course' => '1'),
				array('name' => 'Доллар', 'sign' => 'USD', 'course' => '28.6'),
				array('name' => 'Евро', 'sign' => 'EUR', 'course' => '39.86'),
			)
		));
		$expectedTable = $ds->getTable("currency");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}

	/**
	 * @covers CurrencyModel::delById
	 */
	public function testDelById() {
		$this->object->delById(2);
		$queryTable = $this->getConnection()->createQueryTable(
			'currency', 'SELECT name, sign, course FROM currency'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'currency' => array(
				array('name' => 'Рубль', 'sign' => 'RUB', 'course' => '1'),
				array('name' => 'Евро', 'sign' => 'EUR', 'course' => '39.86'),
			)
		));
		$expectedTable = $ds->getTable("currency");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}
}

?>
