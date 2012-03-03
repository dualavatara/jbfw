<?php

require_once 'tests/utils/PHPUnit_PDO_Database_TestCase.php';
require_once 'tests/utils/PHPUnit_ArrayDataSet.php';

require_once 'model/ArticleModel.php';
require_once 'lib/PDODatabase.php';

/**
 * Test class for ArticleModel.
 * Generated by PHPUnit on 2012-03-03 at 04:18:52.
 */
class ArticleModelTest extends PHPUnit_PDO_Database_TestCase {
	/**
	 * @var ArticleModel
	 */
	protected $object;

	/**
	 * Returns the test dataset.
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet() {
		return new PHPUnit_ArrayDataSet(array('article' => array(array('id'      => 1,
																	   'created' => '2012-02-29 00:30:12',
																	   'name'    => 'test name',
																	   'photo'   => 'img/123.jpg',
																	   'content' => 'test content',
																	   'type'    => 1,
																	   'ord'     => 123,
																	   'flags'   => 1),),));
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ArticleModel(self::getDb());
		parent::setUp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers ArticleModel::getTypes
	 */
	public function testGetTypes() {
		$this->assertEquals(array(ArticleModel::TYPE_ARTICLE  => 'Статья',
								  ArticleModel::TYPE_NEWS     => 'Новости',
								  ArticleModel::TYPE_USEFULL  => 'Полезное',
								  ArticleModel::TYPE_INFO     => 'Информация',
								  ArticleModel::TYPE_CONTACTS => 'Контакты',
								  ArticleModel::TYPE_MISC     => 'Другое'), $this->object->getTypes());
	}

	/**
	 * @covers ArticleModel::getFlags
	 */
	public function testGetFlags() {
		$this->assertEquals(array(ArticleModel::FLAG_VISIBLE => 'Видимый',), $this->object->getFlags());
	}

	/**
	 * @covers ArticleModel::__construct
	 */
	public function testConstruct() {
		$this->assertInstanceOf("DateTimeWithTZField", $this->object->getField("created"));
		$this->assertInstanceOf("CharField", $this->object->getField("name"));
		$this->assertInstanceOf("CharField", $this->object->getField("photo"));
		$this->assertInstanceOf("CharField", $this->object->getField("content"));
		$this->assertInstanceOf("IntField", $this->object->getField("type"));
		$this->assertInstanceOf("IntField", $this->object->getField("ord"));
		$this->assertInstanceOf("IntField", $this->object->getField("flags"));
	}
}

?>