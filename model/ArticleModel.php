<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 5:00
 */

require_once 'lib/model.lib.php';

class ArticleModel extends Model {
	const TYPE_ARTICLE 		= 1;
	const TYPE_NEWS 		= 2;
	const TYPE_USEFULL 		= 3;
	const TYPE_INFO 		= 4;
	const TYPE_CONTACTS		= 5;
	const TYPE_MISC 		= 6;

	const FLAG_VISIBLE		= 0x0001;
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('article', $db);
		$this->field('created', new DateTimeWithTZField('created'));
		$this->field('name', new CharField('name'));
		$this->field('photo', new CharField('photo'));
		$this->field('content', new CharField('content'));
		$this->field('type', new IntField('type'));
		$this->field('ord', new IntField('ord'));
		$this->field('flags', new IntField('flags'));
	}

	public function getTypes() {
		return array(
			self::TYPE_ARTICLE => 'Статья',
			self::TYPE_NEWS => 'Новости',
			self::TYPE_USEFULL => 'Полезное',
			self::TYPE_INFO => 'Информация',
			self::TYPE_CONTACTS => 'Контакты',
			self::TYPE_MISC => 'Другое'
		);
	}

	public function getFlags() {
		return array(
			self::FLAG_VISIBLE => 'Видимый',
		);
	}
}
