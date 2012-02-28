<?php

require_once 'tests/utils/PHPUnit_PDO_Database_TestCase.php';
require_once 'tests/utils/PHPUnit_ArrayDataSet.php';

require_once 'model/PriceModel.php';
require_once 'lib/PDODatabase.php';

/**
 * Test class for PriceModel.
 * Generated by PHPUnit on 2012-02-28 at 04:26:10.
 */
class PriceModelTest extends PHPUnit_PDO_Database_TestCase {
	/**
	 * @var PriceModel
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
			),
			'price' => array(
				array('id' => 1, 'start' => '2011-01-01', 'end' => '2011-01-31', 'currency_id' => 2, 'value' => 99.99, 'flags' => 0x0003),
				array('id' => 2, 'start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 88.88, 'flags' => 0x0001),
				array('id' => 3, 'start' => '1210-03-26', 'end' => '2011-07-03', 'currency_id' => 3, 'value' => 77.77, 'flags' => 0x0002),
				array('id' => 4, 'start' => '1510-03-26', 'end' => '1511-01-31', 'currency_id' => 2, 'value' => 66.66, 'flags' => 0x0000),
			)
		));
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new PriceModel(new PDODatabase($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], "utf8"));
		parent::setUp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers PriceModel::__construct
	 */
	public function testConstruct() {
		$this->assertInstanceOf("Field", $this->object->getField("flags"));
		$this->assertInstanceOf("Field", $this->object->getField("start"));
		$this->assertInstanceOf("Field", $this->object->getField("end"));
		$this->assertInstanceOf("Field", $this->object->getField("currency_id"));
		$this->assertInstanceOf("Field", $this->object->getField("value"));
	}

	/**
	 * @covers PriceModel::getAll
	 */
	public function testGetAll() {
		//		$this->object->get()->all()->exec();
		$this->object->getAll();
		$this->assertEquals(array(
			array('id' => 1, 'start' => '2011-01-01', 'end' => '2011-01-31', 'currency_id' => 2, 'value' => 99.99, 'flags' => 0x0003),
			array('id' => 2, 'start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 88.88, 'flags' => 0x0001),
			array('id' => 3, 'start' => '1210-03-26', 'end' => '2011-07-03', 'currency_id' => 3, 'value' => 77.77, 'flags' => 0x0002),
			array('id' => 4, 'start' => '1510-03-26', 'end' => '1511-01-31', 'currency_id' => 2, 'value' => 66.66, 'flags' => 0x0000),
		), $this->object->data);
	}

	/**
	 * @covers PriceModel::addFromForm
	 */
	public function testAddFromForm() {
		$this->object->addFromForm(array('start' => '2012-02-28', 'end' => '2012-03-01', 'currency_id' => 1, 'value' => 150.50, 'flags' => 0x0003));
		$queryTable = $this->getConnection()->createQueryTable(
			'price', 'SELECT start, end, currency_id, value, flags FROM price'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'price' => array(
				array('start' => '2011-01-01', 'end' => '2011-01-31', 'currency_id' => 2, 'value' => 99.99, 'flags' => 0x0003),
				array('start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 88.88, 'flags' => 0x0001),
				array('start' => '1210-03-26', 'end' => '2011-07-03', 'currency_id' => 3, 'value' => 77.77, 'flags' => 0x0002),
				array('start' => '1510-03-26', 'end' => '1511-01-31', 'currency_id' => 2, 'value' => 66.66, 'flags' => 0x0000),
				array('start' => '2012-02-28', 'end' => '2012-03-01', 'currency_id' => 1, 'value' => 150.50, 'flags' => 0x0003),
			)
		));
		$expectedTable = $ds->getTable("price");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}

	/**
	 * @covers PriceModel::getById
	 */
	public function testGetById() {
		$this->assertEquals(
			array('id' => 2, 'start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 88.88, 'flags' => 0x0001),
			$this->object->getById(2));

		$this->assertFalse($this->object->getById(234));
	}

	/**
	 * @covers PriceModel::saveFromForm
	 */
	public function testSaveFromForm() {
		$form = array('id' => 2, 'start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 123.45, 'flags' => 0x0000);
		$this->object->saveFromForm($form);
		$queryTable = $this->getConnection()->createQueryTable(
			'price', 'SELECT start, end, currency_id, value, flags FROM price'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'price' => array(
				array('start' => '2011-01-01', 'end' => '2011-01-31', 'currency_id' => 2, 'value' => 99.99, 'flags' => 0x0003),
				array('start' => '2010-03-26', 'end' => '1011-01-31', 'currency_id' => 1, 'value' => 123.45, 'flags' => 0x0000),
				array('start' => '1210-03-26', 'end' => '2011-07-03', 'currency_id' => 3, 'value' => 77.77, 'flags' => 0x0002),
				array('start' => '1510-03-26', 'end' => '1511-01-31', 'currency_id' => 2, 'value' => 66.66, 'flags' => 0x0000),
			)
		));
		$expectedTable = $ds->getTable("price");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}

	/**
	 * @covers PriceModel::delById
	 */
	public function testDelById() {
		$this->object->delById(2);
		$queryTable = $this->getConnection()->createQueryTable(
			'price', 'SELECT start, end, currency_id, value, flags FROM price'
		);
		$ds = new PHPUnit_ArrayDataSet(array(
			'price' => array(
				array('start' => '2011-01-01', 'end' => '2011-01-31', 'currency_id' => 2, 'value' => 99.99, 'flags' => 0x0003),
				array('start' => '1210-03-26', 'end' => '2011-07-03', 'currency_id' => 3, 'value' => 77.77, 'flags' => 0x0002),
				array('start' => '1510-03-26', 'end' => '1511-01-31', 'currency_id' => 2, 'value' => 66.66, 'flags' => 0x0000),
			)
		));
		$expectedTable = $ds->getTable("price");
		$this->assertTablesEqual($expectedTable, $queryTable);
	}
}

?>
