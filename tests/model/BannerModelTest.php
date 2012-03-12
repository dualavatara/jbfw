<?php

require_once 'tests/utils/PHPUnit_PDO_Database_TestCase.php';
require_once 'tests/utils/PHPUnit_ArrayDataSet.php';

require_once 'model/BannerModel.php';

/**
 * Test class for BannerModel.
 * Generated by PHPUnit on 2012-03-13 at 01:30:19.
 */
class BannerModelTest extends PHPUnit_PDO_Database_TestCase {
	/**
	 * @var BannerModel
	 */
	protected $object;

	/**
	 * Returns the test dataset.
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet() {
		return new PHPUnit_ArrayDataSet(array(
			'banner' => array(
				array(
					'id' => 1,
					'image' => 'testimagekey.jpg',
					'type' => BannerModel::TYPE_240X100,
					'flags' => BannerModel::FLAG_HEAD
				)
			)
		));
	}


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();
		$this->object = new BannerModel(new PDODatabase($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], "utf8"));
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers BannerModel::__construct
	 */
	public function test__construct() {
		$this->assertInstanceOf("IntField", $this->object->getField("id"));
		$this->assertInstanceOf("IntField", $this->object->getField("type"));
		$this->assertInstanceOf("CharField", $this->object->getField("image"));
		$this->assertInstanceOf("FlagsField", $this->object->getField("flags"));
	}

	/**
	 * @covers BannerModel::getTypes
	 */
	public function testGetTypes() {
		$this->assertEquals(array(
			BannerModel::TYPE_240X100 => '240x100', BannerModel::TYPE_240X350 => '240x350',
		), $this->object->getTypes());
	}

	/**
	 * @covers BannerModel::getFlags
	 */
	public function testGetFlags() {
		$this->assertEquals(array(
			BannerModel::FLAG_HEAD => 'Блок под шапкой', BannerModel::FLAG_LEFTCOL => 'Блок в левой колонке',
		), $this->object->getFlags());
	}

	/**
	 * @covers BannerModel::getSize
	 * @covers BannerSize::__construct
	 */
	public function testGetSize() {
		$this->assertInstanceOf('BannerSize', $this->object->getSize(BannerModel::TYPE_240X100));
		$b1 = new BannerSize(240, 100);
		$b2 = new BannerSize(240, 350);
		$b3 = new BannerSize(0, 0);
		$this->assertEquals($b1, $this->object->getSize(BannerModel::TYPE_240X100));
		$this->assertEquals($b2, $this->object->getSize(BannerModel::TYPE_240X350));
		$this->assertEquals($b3, $this->object->getSize(1312312));
	}
}

?>
