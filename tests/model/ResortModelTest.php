<?php

require_once 'tests/utils/PHPUnit_PDO_Database_TestCase.php';
require_once 'tests/utils/PHPUnit_ArrayDataSet.php';

require_once 'lib/PDODatabase.php';
require_once 'model/ResortModel.php';

/**
 * Test class for ResortModel.
 * Generated by PHPUnit on 2012-03-04 at 01:09:19.
 */
class ResortModelTest extends PHPUnit_PDO_Database_TestCase {
	/**
	 * @var ResortModel
	 */
	protected $object;

	/**
	 * Returns the test dataset.
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet() {
		return new PHPUnit_ArrayDataSet(array(
			'resort' => array(
				array(
					'id'       => 1,
					'name'     => 'test resort name',
					'link'     => 'http://github.com/dualavatara/jbfw',
					'gmaplink' => 'http://maps.google.ru/maps?hl=ru&ll=55.641852,37.800694&spn=0.048827,0.167713&sll=55.641852,37.800694&sspn=0.048827,0.167713&t=h&z=13'
				)
			)
		));
	}


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ResortModel(self::getDb());
		parent::setUp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers ResortModel::__construct
	 */
	public function test__construct() {
		$this->assertInstanceOf("Field", $this->object->getField("name"));
		$this->assertInstanceOf("Field", $this->object->getField("link"));
		$this->assertInstanceOf("Field", $this->object->getField("gmaplink"));
	}
}

?>
