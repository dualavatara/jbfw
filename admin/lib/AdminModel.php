<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 20:53
 */

require_once 'admin/lib/IAdminModel.php';
require_once 'lib/filter.lib.php';

interface IChildParams {
	/**
	 * @abstract
	 * @param AdminField $adminField
	 * @param ModelDataWrapper $row
	 * @return array
	 */
	public function getParams(AdminField $adminField, ModelDataWrapper $row);

	/**
	 * @abstract
	 * @return ISqlFilter
	 */
	public function getFilter();

	/**
	 * @abstract
	 * @param array $request
	 * @return mixed
	 */
	public function getRequestParams($request);
}

class ClassObjectChildParams implements \IChildParams {
	private $classId;
	private $classField;
	private $objectId;
	private $objectField;

	function __construct($request) {
		$this->classId = $request['class_id'];
		$this->classField = $request['class_field'];
		$this->objectId = $request['object_id'];
		$this->objectField = $request['object_field'];
	}

	/**
	 * @param AdminField $adminField
	 * @param ModelDataWrapper $row
	 * @return array
	 */
	public function getParams(\AdminField $adminField, \ModelDataWrapper $row) {
		return array(
			'class_id' => $this->classId,
			'class_field' => $this->classField,
			'object_id' => $row->id,
			'object_field' => $this->objectField,
		);
	}

	/**
	 * @return ISqlFilter
	 */
	public function getFilter() {
		$filter = new \FieldValueSqlFilter();
		$filter->eq($this->classField, $this->classId)->_and()->eq($this->objectField, $this->objectId);
		return $filter;
	}

	/**
	 * @param array $request
	 * @return mixed
	 */
	public function getRequestParams($request) {
		return array(
			'class_id' => $request['class_id'],
			'class_field' => $request['class_field'],
			'object_id' => $request['object_id'],
			'object_field' => $request['object_field'],
		);
	}
}

class ParentChildParams implements \IChildParams {
	private $parentId;
	private $parentField;

	function __construct($request) {
		$this->parentField = $request['parent_field'];
		$this->parentId = isset($request['parent_id']) ? $request['parent_id'] : 0;
	}

	/**
	 * @param AdminField $adminField
	 * @param ModelDataWrapper $row
	 * @return array
	 */
	public function getParams(\AdminField $adminField, \ModelDataWrapper $row) {
		return array(
			'parent_id' => $row->id, 'parent_field' => $this->parentField,
		);
	}

	/**
	 * @return ISqlFilter
	 */
	public function getFilter() {
		$filter = new \FieldValueSqlFilter();
		$filter->eq($this->parentField, $this->parentId);
		return $filter;
	}

	/**
	 * @param array $request
	 * @return mixed
	 */
	public function getRequestParams($request) {
		return array(
			'parent_id' => $request['parent_id'], 'parent_field' => $request['parent_field'],
		);
	}
}

abstract class AdminModel implements IAdminModel {
	/**
	 * @var Model
	 */
	private $model;
	/**
	 * @var array
	 */
	public $fields;

	public $childParamsClass;

	/**
	 * @param Model $model
	 */
	public function __construct(Model $model, $childParamsClass = null) {
		$this->model = $model;
		$this->childParamsClass = $childParamsClass;
	}

	public function onSave($form) {

	}

	function setTemplate($template) {
		foreach ($this->fields as $field) {
			$field->template = $template;
			$field->adminModel = $this;
		}
	}

	/**
	 * Select all object`s rows from database
	 */
	public function getAll() {
		$this->model->get()->all()->exec();
	}

	public function getFiltered($request) {
		if (isset($this->childParamsClass)) {
			$class = $this->childParamsClass;
			$params = new $class($request);
			$this->getModel()->get()->filter($params->getFilter())->exec();
		} else $this->getModel()->get()->all()->exec();
	}

	/**
	 * Adds new object record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function addFromForm($form) {
		$this->model->clear();
		$this->model[0] = $form;
		unset($this->model->data[0]['id']);
		$this->model->insert()->exec();
	}

	/**
	 * Selects object by id
	 * @param $id
	 * @return mixed    array if found, otherwise false
	 */
	public function getById($id) {
		$this->model->get($id)->exec();
		if ($this->model->count()) return $this->model[0]->all();
		return false;
	}

	/**
	 * Saves single object from form array as array('field' => 'value', ...)
	 * $form['id'] is required
	 * @param array $form
	 */
	public function saveFromForm($form) {
		if (isset($form['id'])) {
			$this->model->clear();
			$this->model[0] = $form;
			$this->model->update()->exec();
		}
	}

	/**
	 * Deletes object by id
	 * @param $id
	 */
	public function delById($id) {
		$this->model->get($id)->delete()->exec();
	}

	/**
	 * @return \Model
	 */
	public function getModel() {
		return $this->model;
	}
}

abstract class AdminField {
	public $name;
	public $adminName;
	public $isList;
	public $isListEdit;
	public $isMinWidth;

	public $template;
	public $adminModel;

	public $isForm = true;

	function __construct($name, $adminName, $isList = false, $isListEdit = false, $isMinWidth = false) {
		$this->name = $name;
		$this->adminName = $adminName;
		$this->isList = $isList;
		$this->isListEdit = $isListEdit;
		$this->isMinWidth = $isMinWidth;
	}

	public function input($modelRow) {
		ob_start();
		$this->inputHtml($modelRow);
		return ob_get_clean();
	}

	public function listText($modelRow) {
		ob_start();
		$this->listTextHtml($modelRow);
		return ob_get_clean();
	}

	abstract public function inputHtml($modelRow);

	public function listTextHtml($modelRow) {
		echo $modelRow->{$this->name};
	}

	public function onSave(&$form) {

	}
}

class DefaultAdminField extends AdminField {
	public $size;

	function __construct($name, $adminName, $isList = false, $isListEdit = false, $isMinWidth = false, $size = 50) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->size = $size;
	}

	public function inputHtml($modelRow) {
		?>
	<input id="<?php echo $this->name; ?>" size="<?php echo $this->size; ?>" name="form[<?php echo $this->name; ?>]"
		   value="<?php echo $modelRow->{$this->name}; ?>"/>
	<?php
	}
}

class TagsAdminField extends DefaultAdminField {
	public $size;

	public function inputHtml($modelRow) {
		if ($modelRow) $val = unserialize($modelRow->getRaw()->{$this->name});
		if (is_array($val)) $val = join(',', $val);
		?>
	<input id="<?php echo $this->name; ?>" size="<?php echo $this->size; ?>" name="form[<?php echo $this->name; ?>]"
		   value="<?php echo $val; ?>"/>
	<?php
	}

	public function listTextHtml($modelRow) {
		if ($modelRow) $val = unserialize($modelRow->getRaw()->{$this->name});
		if (is_array($val)) $val = join(',', $val);
		echo $val;
	}
}

class FloatAdminField extends AdminField {
	public function inputHtml($modelRow) {
		?>
	<input id="<?php echo $this->name; ?>" name="form[<?php echo $this->name; ?>]" size="10"
		   value="<?php echo floatval($modelRow->{$this->name}); ?>"/>
	<?php
	}
}

class TextAdminField extends AdminField {
	public function inputHtml($modelRow) {
		?>
	<textarea id="<?php echo $this->name; ?>" name="form[<?php echo $this->name; ?>]" cols="50"
			  rows="20"><?php echo ($modelRow->{$this->name}); ?></textarea>
	<?php
	}
}

class FlagsAdminField extends AdminField {
	public function inputHtml($modelRow) {
		if ($this->adminModel->getModel()->getFlags()) {
			$this->template->insertTemplate('Form\FlagsField', array(
				//'title' => $this->adminName,
				'name' => "form[{$this->name}]",
				'value' => $modelRow->flags,
				'flags' => $this->adminModel->getModel()->getFlags()
			));
		}
	}

	public function listTextHtml($modelRow) {
		if ($this->adminModel->getModel()->getFlags()) {
			$flags = $modelRow->getModel()->getFlags();
			$flag = array();
			foreach ($flags as $k => $v) if ($modelRow->flags->check($k)) $flag[] = $v;
			echo implode(',', $flag);
		}
		;
	}
}

class CustomFlagsField extends AdminField {
	public $func;

	function __construct($name, $adminName, $func, $isList, $isListEdit = false, $isMinWidth = false) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->func = $func;
	}

	public function inputHtml($modelRow) {
		$this->template->insertTemplate('Form\FlagsField', array(
			//'title' => $this->adminName,
			'name' => "form[{$this->name}]",
			'value' => $modelRow->{$this->name},
			'flags' => $this->adminModel->getModel()->{$this->func}()
		));
	}

	public function listTextHtml($modelRow) {
		$flags = $modelRow->getModel()->{$this->func}();
		$flag = array();
		foreach ($flags as $k => $v) if ($modelRow->{$this->name}->check($k)) $flag[] = $v;
		echo implode(',', $flag);
	}
}

class SelectAdminField extends AdminField {
	public $callback;
	public $class;

	function __construct($name, $adminName, $callback, $isList, $isListEdit = false, $isMinWidth = false) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->callback = $callback;
	}

	public function inputHtml($modelRow) {
		$this->template->insertTemplate('Form\SelectField', array(
			'name' => $this->name,
			'selected' => $modelRow->{$this->name},
			'values' => $this->adminModel->getModel()->{$this->callback}(),
		));
	}

	public function listTextHtml($modelRow) {
		$arr = $modelRow->getModel()->{$this->callback}();
		if (isset($arr[$modelRow->{$this->name}])) echo $arr[$modelRow->{$this->name}];
	}
}

class SearchSelectAdminField extends AdminField {
	//public $callback;
	public $class;

	function __construct($name, $adminName, $class, $isList, $isListEdit = false, $isMinWidth = false) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->class = $class;
	}

	public function inputHtml($modelRow) {
		$this->template->insertTemplate('Form\SearchSelectField', array(
			'name' => "form[{$this->name}]",
			'value' => $modelRow->{$this->name},
			'rest_url' => '/admin/' . strtolower($this->class) . '/json'
		));
	}

	public function listTextHtml($modelRow) {
		$class = '\\model\\' . $this->class;
		$basemodel = $modelRow->getModel()->getRaw();
		$m = new $class($basemodel->db);
		$m = $m->getModel();
		$m->get($modelRow->{$this->name})->exec();
		if ($m->count()) echo $m[0]->name;
		//$arr = $modelRow->getModel()->{$this->callback}();
		//if (isset($arr[$modelRow->{$this->name}])) echo $arr[$modelRow->{$this->name}];
	}
}


class RefAdminField extends AdminField {
	public $class;
	public $childParams;
	public $fromRoute;

	function __construct($name, $adminName, IChildParams $childParams, $isList, $isListEdit = false, $isMinWidth = false) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->isForm = false;
		$this->childParams = $childParams;
	}

	public function inputHtml($modelRow) {

	}

	public function listTextHtml($modelRow) {
		$params = array_merge($this->childParams->getParams($this, $modelRow->getRaw()), array(
			'is_child' => true, 'from_route' => $_SERVER['REQUEST_URI'],
		));
		$this->template->showLink('список', strtolower($this->class) . '_list', $params);
	}
}

class BackrefAdminField extends AdminField {
	public $value;

	function __construct($name, $adminName, $value, $isList, $isListEdit = false, $isMinWidth = false) {
		parent::__construct($name, $adminName, $isList, $isListEdit, $isMinWidth);
		$this->value = $value;
	}

	public function inputHtml($modelRow) {
		$value = $this->value;
		if ($modelRow) $value = $modelRow->{$this->name};
		?>
	<input type="hidden" id="<?php echo $this->name; ?>" name="form[<?php echo $this->name; ?>]"
		   value="<?php echo $value; ?>"/>
	<?php
		echo $value;
	}

	public function listTextHtml($modelRow) {
		echo $modelRow->{$this->name};
	}
}

class ImageAdminField extends AdminField {
	public function inputHtml($modelRow) {
		$this->template->insertTemplate('Form\ImageField', array(
			'name' => $this->name, 'key' => $modelRow->{$this->name},
		));
	}

	public function listTextHtml($modelRow) {
		if ($modelRow->{$this->name}) $this->template->showLink($modelRow->{$this->name}, 'static', array('key' => $modelRow->{$this->name}), 'target="_blank"');
	}

	public function onSave(&$form) {
		parent::onSave($form);
		/*
		 foreach($_FILES as $key => $fparam) {
			if ($this->model->getModel()->getField($key)) {
				$is = new \ImageStorage(getcwd() . '/../' . PATH_DATA);
				$imgkey = $is->storeImage($key);
				if ($imgkey) $form[$key] = $imgkey;
			}
		}
		 */
		foreach ($_FILES as $key => $fparam) {
			if ($this->name == $key) {
				$is = new \ImageStorage(getcwd() . '/../' . PATH_DATA);
				$imgkey = $is->storeImage($key);
				if ($imgkey) $form[$key] = $imgkey;
			}
		}
	}

}

class ImageThumbnailAdminField extends AdminField {
	public $refName;
	public $width;
	public $height;

	function __construct($name, $refName, $width, $height, $adminName, $isList = false) {
		parent::__construct($name, $adminName, $isList, false, false);
		$this->refName = $refName;
		$this->width = $width;
		$this->height = $height;
	}

	public function inputHtml($modelRow) {
		if ($modelRow->{$this->name}) $this->template->showLink($modelRow->{$this->name}, 'static', array('key' => $modelRow->{$this->name}), 'target="_blank"');
	}

	public function listTextHtml($modelRow) {
		if ($modelRow->{$this->name}) $this->template->showLink($modelRow->{$this->name}, 'static', array('key' => $modelRow->{$this->name}), 'target="_blank"');
	}

	public function onSave(&$form) {
		parent::onSave($form);
		foreach ($_FILES as $key => $fparam) {
			if ($this->refName == $key) {
				$is = new \ImageStorage(getcwd() . '/../' . PATH_DATA);
				$imgkey = $is->storeImageThumbnail($key, $this->width, $this->height);
				if ($imgkey) $form[$this->name] = $imgkey;
			}
		}
	}

}

class DateTimeAdminField extends AdminField {
	public function inputHtml($modelRow) {
		/*	$this->template->insertTemplate('Form\SelectField', array(
			'name' => $this->name,
			'selected' => $modelRow->type,
			'values' => $this->adminModel->getModel()->{$this->callback}(),
		));*/
		$now = new DateTime();
		$date = array(
			'name' => $this->name, 'value' => $modelRow ? $modelRow->{$this->name} : $now->format(DateTime::ISO8601)
		);
		$this->template->insertTemplate('Form\DateTimeField', $date);
	}

	/*public function listTextHtml($modelRow) {
		$arr = $modelRow->getModel()->{$this->callback}();
		if (isset($arr[$modelRow->{$this->name}])) echo $arr[$modelRow->{$this->name}];
	}*/
}

class FieldInfoAdminField extends AdminField {
	public $pattern;

	function __construct($pattern, $adminName, $isList = false, $isListEdit = false, $isMinWidth = false, $size = 50) {
		parent::__construct('', $adminName, $isList, $isListEdit, $isMinWidth);
		$this->pattern = $pattern;
	}

	public function inputHtml($modelRow) {
		if ($modelRow) {
			$m = $modelRow->getModel()->getRaw();
			$pattern = $this->pattern;
			foreach ($m->getFields() as $field) {
				$v = $modelRow->getRaw()->{$field->name};
				$pattern = preg_replace('/{' . $field->name . '}/', $v, $pattern);
			}
			?>
		<a href="<?php echo $pattern; ?>"><?php echo $pattern; ?></a>
		<?php
		}
	}

	public function listTextHtml($modelRow) {
		$this->inputHtml($modelRow);
	}
}
