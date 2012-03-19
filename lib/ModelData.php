<?php
/**
 * User: dualavatara
 * Date: 3/19/12
 * Time: 11:03 PM
 */

require_once 'lib/field.lib.php';
require_once 'lib/ModelDataWrapper.php';

define("MODEL_ID_FIELD_NAME", "id");

/**
 *
 */
class ModelException extends Exception { }

/**
 *
 */
class ModelData implements arrayaccess, Iterator{
	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @var array
	 */
	public $data = array();
	/**
	 * @var Field[]
	 */
	public $fields = array();
	/**
	 * @var null
	 */
	public $table = null;

	/**
	 * @param $table
	 */
	public function __construct($table) {
		$this->table = $table;
		$this->fields[MODEL_ID_FIELD_NAME] = new IntField(MODEL_ID_FIELD_NAME, Field::PRIMARY_KEY);
	}

	/**
	 * @param Field $field
	 * @param null $name
	 * @throws ModelException
	 */
	public function field(Field $field, $name = null) {
		if (!isset($name)) $name = $field->name;
		if ($name == MODEL_ID_FIELD_NAME) throw new ModelException("Can`t use default ".MODEL_ID_FIELD_NAME);
		$this->fields[$name] = $field;
	}

	/**
	 * @param $offset
	 * @return bool
	 */
	final public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	/**
	 * @param $offset
	 * @return ModelDataWrapper
	 * @throws ModelException
	 */
	final public function offsetGet($offset) {
		if($this->offsetExists($offset)) return new ModelDataWrapper($this, $offset);
		throw new ModelException('Invalid ModelData offset.');
	}

	/**
	 * @param $offset
	 * @param $value
	 */
	final public function offsetSet($offset, $value) {
		if (!isset($this->data[$offset])) $this->data[$offset] = array();

		foreach ($this->fields as $key => $field) {
			if (isset($value[$key])) $this->data[$offset][$key] = $field->rawvalue($value[$key]);
			else $this->data[$offset][$key] = null;
		}
	}

	/**
	 * @param $offset
	 */
	final public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	/**
	 * @return ModelDataWrapper
	 */
	final public function current () { return new ModelDataWrapper($this, $this->position); }

	/**
	 * @return int
	 */
	final public function key () { return $this->position; }

	/**
	 *
	 */
	final public function next () { ++$this->position; }

	/**
	 *
	 */
	final public function rewind () { $this->position = 0; }

	/**
	 * @return bool
	 */
	final public function valid () { return isset($this->data[$this->position]); }

	/**
	 * @param $name
	 * @return array
	 * @throws ModelException
	 */
	final public function __get($name) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$res = array();
		foreach ($this->data as $row) {
			$res[] = $this->fields[$name]->value($row[$name]);
		}
		return $res;
	}

	/**
	 * @param $name
	 * @param $value
	 * @throws ModelException
	 */
	final public function __set($name, $value) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$field = $this->fields[$name]->name;
		if (!is_array($value)) $value = array($value);
		for($i = count($this->data); $i < count($value); $i++) $this->data[] = array();
		reset($this->data);
		reset($value);
		foreach($this->data as &$row) {
			list(, $val) = each($value);
			$row[$field] = $val;
		}
		//foreach ($value as $val) $this->data[$field] = strval($val);
	}

	/**
	 * @param $name
	 * @return bool
	 */
	final public function __isset($name) {
		if (!isset($this->fields[$name])) return false;
		$field = $this->fields[$name]->name;
		foreach ($this->data as $row) if (isset($row[$field])) return true;
		return false;
	}

	/**
	 * @param $name
	 * @throws ModelException
	 */
	final public function __unset($name) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$field = $this->fields[$name]->name;
		foreach ($this->data as &$row) $row[$field] = null;
	}

	/**
	 *
	 */
	public function clear() {
		$this->data = array();
	}

	/**
	 * @return int
	 */
	public function count() {
		return count($this->data);
	}
}
