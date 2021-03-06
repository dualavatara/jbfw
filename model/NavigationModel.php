<?php
/**
 * User: dualavatara
 * Date: 4/13/12
 * Time: 2:03 PM
 */

require_once 'lib/model.lib.php';

class NavigationModel extends Model {
	const ID_MENU				= 1;
	const ID_FOOTERLEFT			= 2;
	const ID_FOOTERRIGHT		= 3;

	const FLAG_VISIBLE = 0x0001;
	const FLAG_BLANK = 0x0002;

	public function __construct(IDatabase $db) {
		parent::__construct('navigation', $db);
		$this->field(new IntField('parent_id'));
		$this->field(new CharField('name'));
		$this->field(new CharField('link'));
		$this->field(new IntField('ord'));
		$this->field(new FlagsField('flags'));
	}

	public function getFlags() {
		return array(
			self::FLAG_VISIBLE => 'Видимый',
			self::FLAG_BLANK => 'Новое окно',
		);
	}

	public function fixedIds() {
		return array(
			self::ID_MENU,
			self::ID_FOOTERLEFT,
			self::ID_FOOTERRIGHT
		);
	}

	public function byId($id) {
		foreach($this as $row) {
			if ($row->id == $id) return $row;
		}
		return null;
	}

	public function byParentId($parentId) {
		$res = array();
		foreach($this as $row) {
			if ($row->parent_id == $parentId) $res[] = $row;
		}
		return $res;
	}
}
