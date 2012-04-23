<?php
/**
 * User: dualavatara
 * Date: 4/18/12
 * Time: 1:24 PM
 */
require_once 'lib/model.lib.php';

class ArticleImageModel extends Model {
	const FLAG_MAIN		= 0x0001;

	public function __construct(IDatabase $db) {
		parent::__construct('article_image', $db);

		$this->field(new CharField('thumbnail'));
		$this->field(new CharField('thumbnail50'));
		$this->field(new CharField('thumbnail125'));
		$this->field(new CharField('thumbnail200'));
		$this->field(new CharField('image'));
		$this->field(new CharField('alt'));
		$this->field(new IntField('article_id'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			//self::FLAG_MAIN => 'Главное',
		);
	}
}
