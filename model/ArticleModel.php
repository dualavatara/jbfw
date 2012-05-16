<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 5:00
 */

require_once 'lib/model.lib.php';
require_once 'model/ArticleImageModel.php';

class ArticleModel extends Model {
	const TYPE_ARTICLE = 1;
	const TYPE_NEWS = 2;
	const TYPE_USEFULL = 3;
	const TYPE_INFO = 4;
	const TYPE_CONTACTS = 5;
	const TYPE_MISC = 6;

	const FLAG_VISIBLE = 0x0001;
	const FLAG_FOOTER = 0x0002;
	const FLAG_TOINDEX = 0x0004;

	/**
	 * @var ArticleImageModel
	 */
	public $image;

	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('article', $db);
		$this->field(new DateTimeWithTZField('created'));
		$this->field(new CharField('name'));
		$this->field(new CharField('photo'));
		$this->field(new CharField('alt'));
		$this->field(new CharField('photo_preview'));
		$this->field(new CharField('content', Field::STRIP_SLASHES));
		$this->field(new CharField('content_short', Field::STRIP_SLASHES));
		$this->field(new IntField('type'));
		$this->field(new CharField('maintag'));
		$this->field(new CharField('tags'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));

		$this->image = new ArticleImageModel($db);
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
			self::FLAG_VISIBLE => 'Видимый', self::FLAG_TOINDEX => 'На главную',
		);
	}

	public function getOtherImages($idx) {
		$this->image->get()->filter($this->image->filterExpr()->notEq('flags', ArticleImageModel::FLAG_MAIN)->_and()
			->eq('article_id', $this[$idx]->id))->exec();
		if ($this->image->count()) return $this->image;
		return array();
	}

	public function getByTags($tags, $tagsField, $skipId = '') {
		if (is_array(unserialize($tags))) $tarr = unserialize($tags);
		else {
			$tarr = explode(',', $tags);
			array_walk($tarr, function(&$val, $key) {
				$val = trim($val);
			});
		}
		$nt = array();
                foreach($tarr as $t) if ($t) $nt[] = $t;
                $tarr = $nt;
		if(empty($tarr)) return $this;

		$filter = $this->filterExpr();
		$first = true;
		foreach ($tarr as $tag) {
			if ($first) $first = false; else $filter->_or();
			$filter->like($tagsField, "%$tag%");
		}

		$this->get()->filter($filter)->filter($this->filterExpr()->eq('flags', ArticleModel::FLAG_VISIBLE))->exec();

		$newdata = array();
		$addedIdx = array();
		foreach ($this as $row) {
			$arr = unserialize($row->$tagsField);
			foreach ($tarr as $tag) {
				if (is_array($arr) && in_array($tag, $arr) && !in_array($row->offset, $addedIdx) && $skipId != $row->id) {
					$newdata[] = $this->data[$row->offset];
					$addedIdx[] = $row->offset;
				}
			}
		}
		$this->data = $newdata;
		return $this;
	}
}
