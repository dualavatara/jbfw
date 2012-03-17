<?
require_once('config/config.php');
require_once('lib/db.lib.php');
require_once('lib/field.lib.php');
require_once('lib/filter.lib.php');
require_once('lib/argument.lib.php');

/**
 * require SQL command wrappers
 */
require_once("lib/InsertSqlCmd.php");
require_once("lib/UpdateSqlCmd.php");
require_once("lib/DeleteSqlCmd.php");
require_once("lib/SelectSqlCmd.php");
require_once("lib/CallSqlCmd.php");

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
		if(isset($this->data[$offset])) return new ModelDataWrapper($this, $offset);
		throw new ModelException('Invalid ModelData offset.');
	}

	/**
	 * @param $offset
	 * @param $value
	 */
	final public function offsetSet($offset, $value) {
		if (!is_array($value)) $value = array($value);
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
	 * @param $name
	 * @param null $func
	 * @return array
	 * @throws ModelException
	 */
	public function column($name, $func = null) {
    	if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$res = array();
		foreach ($this->data as $row) {
			if ($func) $res[] = $func($this->fields[$name]->value($row[$this->fields[$name]->name]));
			else $res[] = $this->fields[$name]->value($row[$this->fields[$name]->name]);
		}
		return $res;
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

	/**
	 * @param $fromIdx
	 * @param $toIdx
	 * @return ModelData
	 */
	public function slice($fromIdx, $toIdx) {
    	$nm = clone $this;//new static($this->table);
    	$nm->data = array();
    	$inc = $fromIdx > $toIdx ? -1 : 1;
    	for($i = $fromIdx; $i <= $toIdx; $i += $inc){
    		if (isset($this->data[$i])) $nm->data[] = $this->data[$i];
    	}

    	return $nm;
    }
}

/**
 *
 */
class ModelDataWrapper {
	/**
	 * @var int
	 */
	private $offset = 0;
	/**
	 * @var ModelData|null
	 */
	private $model = null;

	/**
	 * @param ModelData $model
	 * @param $offset
	 */
	public function __construct(ModelData &$model, $offset) {
		$this->model = $model;
		$this->offset = $offset;
	}

	/**
	 * @param $name
	 * @throws ModelException
	 */
	private function assertField($name) {
		if (!isset($this->model->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this->model));
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		$this->assertField($name);
		return $this->model->fields[$name]->value($this->model->data[$this->offset][$name]);
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value) {
		$this->assertField($name);
		$this->model->data[$this->offset][$name] = $this->model->fields[$name]->rawvalue($value);
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function __isset($name) {
		try {
			$this->assertField($name);
		} catch (ModelException $e) { return false; }
		return isset($this->model->data[$this->offset][$name]);
	}

	/**
	 * @param $name
	 */
	public function __unset($name) {
    	$this->assertField($name);
    	unset($this->model->data[$this->offset][$name]);
    }

	/**
	 * @return mixed
	 */
	public function all() {
    	return $this->model->data[$this->offset];
    }

	/**
	 * @param null $fields
	 * @return array
	 */
	public function allTyped($fields = null) {
        $res = array();
        foreach($this->model->fields as $name => $field) {
	        if((count($fields) && in_array($name, $fields)) || (!count($fields))) {
	            $res[$name] = $field->value($this->model->data[$this->offset][$name]);
		        if ($res[$name] instanceof DateTime) {
			        $res[$name] = $res[$name]->__toString();
		        }
	        }
        }
        return $res;
    }
}

/**
 *
 */
class Model extends ModelData {
	/**
	 * @var IDatabase|null
	 */
	public $db = null;
	/**
	 * @var ISqlCmd
	 */
	public $sqlCmd = null;
	/**
	 * @var array
	 */
	public $orders = array();
	/**
	 * @var int
	 */
	public $_limit = 2000;
	/**
	 * @var int
	 */
	public $_offset = 0;
	/**
	 * @var null
	 */
	public $distinct = null;

	/**
	 * @var OSCollectionRequest
	 */
	public $_filterOS = null;

	/**
	 * @var bool
	 */
	public $silentCatchDBExceptions = false;
	
	/**
	 * @var ISqlFilter
	 */
	public $filterObj = null;

	/**
	 * @var array[int]Argument
	 */
	public $args = array();

	/**
	 * @return array
	 */
	public function __sleep() {
		return array('data', 'fields', 'table');
	}

	/**
	 * @param $table
	 * @param IDatabase $db
	 */
	public function __construct($table, IDatabase $db) {
		$this->db = $db;
		parent::__construct($table);
		$this->fields[MODEL_ID_FIELD_NAME] = new IntField(MODEL_ID_FIELD_NAME, Field::PRIMARY_KEY);

	}

	/**
	 *
	 */
	public function __clone() {
		if (isset($this->sqlCmd))
			$this->sqlCmd = clone $this->sqlCmd;
		if (isset($this->filterObj))
			$this->filterObj = clone $this->filterObj;
	}


	/**
	 * @param IDatabase $db
	 * @codeCoverageIgnore
	 */
	public function setDb(IDatabase $db) {
		$this->db = $db;
	}

	/**
	 * @return IDatabase|null
	 * @codeCoverageIgnore
	 */
	public function getDb() { return $this->db; }

	/**
	 * @return array
	 */
	public function getFields() {
		$ret = array();
		foreach ($this->fields as $f) if ($f->definition($this->db, $this->table)) $ret[] = $f;

		return $ret;
	}

	/**
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function getArgs() {
		return $this->args;
	}

	/**
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function &getData() { return $this->data; }

	/**
	 * @return Field[]
	 */
	public function getPKey() {
		$fields = array();
		foreach ($this->fields as $name => $field) {
			if ($field->is(Field::PRIMARY_KEY)) {
				$fields[$name] = $field;
			}
		}

		return $fields;
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
	 * @param Argument $arg
	 */
	public function arg(Argument $arg) {
		$this->args[] = $arg;
	}

	/**
	 * 
	 * Returns field object on the given name
	 * @param string $name
	 * @return Field
	 */
	public function getField($name) {
		if (!isset($this->fields[$name])) return false;
		return $this->fields[$name];
	}

	/**
	 * @return ISqlFilter|null
	 * @codeCoverageIgnore
	 */
	public function getFilter() { return $this->filterObj; }

	/**
	 * @return Model
	 */
	public function insert() {
		$this->sqlCmd = new InsertSqlCmd();
		return $this; 
	}

	/**
	 * @param $stProsName
	 * @return Model
	 */
	public function call($stProsName) {
		$reflectionObj = new ReflectionClass('CallSqlCmd');
		$this->sqlCmd = $reflectionObj->newInstanceArgs(func_get_args());
		return $this;
	}

	/**
	 * @return Model
	 */
	public function update() {
		$this->sqlCmd = new UpdateSqlCmd();
		foreach($this->fields as $f) {
			if ($f->is(Field::PRIMARY_KEY)) {
				$this->filter(new PKeySqlFilter());
				break;
			}
		}
		return $this;
	}

	/**
	 * @param null $pkeyValues
	 * @return Model
	 */
	public function get($pkeyValues = null) {
		$this->sqlCmd = new SelectSqlCmd();
		if ($pkeyValues !== null) {
			if (!is_array($pkeyValues)) $pkeyValues = array($pkeyValues);
			$this->filter(new PKeyValuesSqlFilter($pkeyValues));
		}
		$this->orders = array();
		return $this; 
	}

	/**
	 * @param $field
	 */
	public function distinct($field) {
		if($this->sqlCmd instanceof SelectSqlCmd) {
			$this->distinct  = $field;
		}
	}

	/**
	 * @return Model
	 */
	public function delete() {
		$this->sqlCmd = new DeleteSqlCmd();
		return $this; 
	}

	/**
	 * @param $filter
	 * @return Model
	 * @throws ModelException
	 */
	public function filter($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		if (!$this->filterObj) $this->filterObj = $filter;
		else $this->filterObj = new AndCompositeSqlFilter($this->filterObj, $filter);
		return $this;
	}

	/**
	 * @param $filter
	 * @return Model
	 * @throws ModelException
	 */
	public function filterSet($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		$this->filterObj = $filter;
		return $this;
	}

	/**
	 * @param $filter
	 * @return Model
	 * @throws ModelException
	 */
	public function exclude($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		if (!$this->filterObj) $this->filterObj = $filter;
		else $this->filterObj = new AndCompositeSqlFilter($this->filterObj, new NotCompositeSqlFilter($filter));
		return $this;
	}

	/**
	 *
	 */
	public function getSql() {
		return $this->sqlCmd->sql($this);
	}

	/**
	 *
	 */
	public function reset() {
		$this->sqlCmd = null;
		$this->filterObj = null;
		$this->orders = array();
		$this->_limit = 2000;
		$this->_offset = 0;
	}

	/**
	 * @param bool $async
	 * @return Model
	 * @throws ModelException
	 */
	public function exec($async = false) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using exec().");
		$res = $this->sqlCmd->exec($this, $async);
		$this->reset();
		return $this;
	}

	/**
	 *@codeCoverageIgnore
	 */
	public function lastSql() {
		return $this->db->getLastQuery();
	}

	/**
	 * @param $name
	 * @param bool $desc
	 * @return Model
	 * @throws ModelException
	 */
	public function order($name, $desc = false) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		if ($desc) $this->orders[] = array('name' => $this->fields[$name]->name, 'flag' => Field::ORDER_DESC);
		else $this->orders[] = array('name' => $this->fields[$name]->name, 'flag' => Field::ORDER_ASC);
		return $this;
	}
	
    /*
     * filter generation functions
     */
	/**
	 * @return AllSqlFilter
	 */
	public function filterAll() { return new AllSqlFilter(); }

	/**
	 * @return Model
	 */
	public function all() {return $this->filter(new AllSqlFilter());}

	/**
	 * @return FieldsSqlFilter
	 */
	public function filterFields() { return new FieldsSqlFilter(func_get_args()); }

	/**
	 * @return FieldValueSqlFilter
	 */
	public function filterExpr() { return new FieldValueSqlFilter(); }

	/**
	 * @return string
	 */
	public function definition() {
		$s = array();
		foreach ($this->fields as $field) {
			$s[] = $field->definition($this->db, $this->table);
		}
		$sql = 'CREATE TABLE ' . $this->db->quot($this->table) . '('.implode(', ', $s) . ') WITH (OIDS=FALSE);';
		return $sql;
	}

	/**
	 * @param $fieldName
	 * @param $value
	 * @return bool
	 */
	public function row($fieldName, $value) {
		$field = $this->fields[$fieldName]; 
		foreach ($this->data as $key => $row) {
			/** @noinspection PhpUndefinedMethodInspection */
			if ($field->value($row[$field->name]) == $value) return $this[$key];
		}
		return false;
	}

	/**
	 * @param $limit
	 * @return Model
	 * @codeCoverageIgnore
	 */
	public function limit($limit) {
		$this->_limit = $limit;
		return $this; 
	}

	/**
	 * @param $offset
	 * @return Model
	 * @codeCoverageIgnore
	 */
	public function offset($offset) {
		$this->_offset = $offset;
		return $this; 
	}
}

/**
 *
 */
class OSCollection implements ArrayAccess {
	/**
	 * @var int
	 */
	public $startIndex = 0;
	/**
	 * @var null
	 */
	public $itemsPerPage = null;
	/**
	 * @var int
	 */
	public $totalResults = 0;
	/**
	 * @var array
	 */
	protected $entry = array();
	/**
	 * @var bool
	 */
	public $filtered = false;
	/**
	 * @var bool
	 */
	public $sorted = false;
	/**
	 * @var bool
	 */
	public $updatedSince = false;

	/**
	 * @param $entries
	 */
	public function setEntries($entries) {
		$this->entry = $entries;
	}

	/**
	 * @param $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return isset($this->entry[$offset]);
	}

	/**
	 * @param $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->entry[$offset];
	}

	/**
	 * @param $offset
	 * @param $value
	 */
	public function offsetSet($offset, $value) {
		$this->entry[$offset] = $value;
	}

	/**
	 * @param $offset
	 */
	public function offsetUnset($offset) {
		unset($this->entry[$offset]);
	}

	/**
	 * @return object
	 */
	public function getObject() {
		$resarr = array(
			'entry' => $this->entry,
			'totalResults' => $this->totalResults,
			'filtered' => $this->filtered,
			'sorted' => $this->sorted,
			'updatedSince' => $this->updatedSince
		);
		if (isset($this->itemsPerPage)) {
			$resarr['itemsPerPage'] = $this->itemsPerPage;
			$resarr['startIndex'] = $this->startIndex;
		}


		$result = (object) $resarr;
		return $result;
	}
}

/**
 *
 */
class OSCollectionRequest implements ISqlFilter {
	/**
	 * @var null
	 */
	public $count = null;
	/**
	 * @var null
	 */
	public $filterBy = null;
	/**
	 * @var null
	 */
	public $filterOp = null;
	/**
	 * @var null
	 */
	public $filterValue = null;
	/**
	 * @var null
	 */
	public $sortBy = null;
	/**
	 * @var null
	 */
	public $sortOrder = null;
	/**
	 * @var int
	 */
	public $startIndex = 0;

	/**
	 * @param null $request
	 */
	public function __construct($request = null) {
		if (is_array($request)) {
			$params = get_object_vars($this);
			foreach($params as $key => $val) {
				if (isset($request[$key])) $this->$key = $request[$key];
			}
		}
	}

	/**
	 * @param Model $model
	 * @param $row
	 * @return string
	 */
	public function sql(Model $model, $row) {
		if (null == $this->filterBy)
			return '(true)';
		if (!isset($model->fields[$this->filterBy])) return '(false)';

		$fb = $model->fields[$this->filterBy]->get_name();
		$quotedField = $model->db->quot($model->db->escape($fb));
		
		switch($this->filterOp) {
				case 'contains': {
					if (!is_array($this->filterValue)) {
						$quotedValue = $model->db->quot($model->db->escape('%' . $this->filterValue . '%'), true);
						return sprintf('(%s LIKE %s)', $quotedField, $quotedValue);
					} else return '(false)';
					}; break;
				case 'equals': {
					if (!is_array($this->filterValue)) {
						$quotedValue = $model->db->quot($model->db->escape($this->filterValue), true);
						return sprintf('(%s = %s)', $quotedField, $quotedValue);
					} else return '(false)';
					}; break;
				case 'in': {
					if (is_array($this->filterValue)) {
						$values = array();
						foreach($this->filterValue as $value) $values[] = $model->db->quot($model->db->escape($value), true);
						$quotedValue = join(', ', $values);
						return sprintf('(%s IN (%s))', $quotedField, $quotedValue);
					} else return '(false)';
					}; break;
				case 'startsWith': {
					if (!is_array($this->filterValue)) {
						$quotedValue = $model->db->quot($model->db->escape($this->filterValue . '%'), true);
						return sprintf('(%s LIKE %s)', $quotedField, $quotedValue);
					} else return '(false)';
					}; break;
				case 'present': {
						return sprintf('(%s != \'\' AND %s IS NOT NULL)', $quotedField, $quotedField);
					}; break;
				default :
					return '(true)';
					break;
			}
	}
}