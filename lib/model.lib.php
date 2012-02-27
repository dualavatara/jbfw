<?
require_once('config/config.php');
require_once('lib/db.lib.php');
require_once('lib/field.lib.php');
require_once('lib/filter.lib.php');
require_once('lib/argument.lib.php');

class ModelException extends Exception { }

interface ISqlCmd {
	public function sql(Model $model);
	public function exec(Model $model, $async = false);
}

/**
 * @todo Create constructor and pass stored procedure name and arguments to it
 */
class CallSqlCmd implements ISqlCmd{

	protected $stProsName;

	public function __construct($stProsName) {
		if (!$stProsName) throw new ModelException('Stored Prosedure name undefined');

		$this->stProsName = $stProsName;

		foreach(func_get_args() as $k => $v)
			if ($k != 0)
				$this->args[] = '\'' . $v . '\'';
	}

	public function sql(Model $model) {
		$operator = 'SELECT ';
		if (count($model->fields)) {
			$fields = array();
			foreach($model->fields as $field)
				$fields[] = $model->getDb()->quot($field->name);
			$operator .= implode(', ', $fields) . ' FROM ';
		}
		$sql = $operator . $model->getDb()->quot($this->stProsName) .
			   '( ' . implode(', ', $this->args) . ');';
		return $sql;
	}

	public function exec(Model $model, $async = false) {
		$res = array();
		$model->db->getQueryArray($this->sql($model), $async, $res);
		$model->data = array();
		foreach ($res as $row) {
			$rec = array();
			foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
			$model->data[] = $rec;
		}
	}
}

class InsertSqlCmd implements ISqlCmd{
	public function sql(Model $model) {
		$sql = '';
		foreach ($model->data as $key => $row) $sql .= $this->insertSql($model, $row);
		
		return $sql;
	}
	
	public function insertSql($model, $row) {
		$sql = 'INSERT INTO ' . $model->db->quot($model->table);
		$fields = array();
		$values = array();
		foreach ($model->fields as $key => &$field) {
			if (!isset($row[$key])) continue;
			$fields[] = $model->db->quot($field->name);
			$values[] = $field->quotEscapeValue($model->db, $row[$key]);//$model->db->quot($model->db->escape($row[$key]), true);
		}
		if (!empty($fields)) $sql .= '(' . implode(', ', $fields) . ') VALUES(' . implode(', ', $values) . ')';
		
		$sql .= ' RETURNING *;';
		return $sql;
	}
	
	public function exec(Model $model, $async = false) {
		$ret = array(); 
		foreach ($model->data as $record) {
			$res = array();
			try {
				$model->db->getQueryArray($this->insertSql($model, $record), $async, $res);
			} catch (Exception $e) {
				if ($model->silentCatchDBExceptions) continue;
				throw $e;
			}
			foreach ($res as $row) {
				$rec = array();
				foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
				$ret[] = $rec;
			}
		}
		$model->data = $ret;
	}
}

class UpdateSqlCmd implements ISqlCmd{
	public function sql(Model $model) {
		$sql = '';
		foreach ($model->data as $key => $row) $sql .= $this->updateSql($model, $row);
		
		return $sql;
	}
	/**
	 * 
	 * generate sql for each updated row
	 * @param Model $model
	 * @param array $row
	 * @return string
	 */
	public function updateSql(Model $model, $row) {
		$sql = 'UPDATE ' . $model->db->quot($model->table);
		$fields = array();
		foreach ($model->fields as $key => &$field) {
			if (!isset($row[$key])) continue;
			$fields[] = $model->db->quot($field->name) . ' = ' . $model->db->quot($model->db->escape($row[$key]), true);
		}
		
		if (!empty($fields)) {
			$sql .= ' SET ' . implode(', ', $fields);
		}
		$where = $model->getFilter()->sql($model, $row);
		if ($where) $sql .= ' WHERE '.$where; 
		$sql .= ' RETURNING *;';
		return $sql;
	}
	
	public function exec(Model $model, $async = false) {
		$ret = array(); 
		foreach ($model->data as $record) {
			$res = array();
			try {
				$model->db->getQueryArray($this->updateSql($model, $record), $async, $res);
			} catch (Exception $e) {
				if ($model->silentCatchDBExceptions) continue;
				throw $e;
			}
			foreach ($res as $row) {
				$rec = array();
				foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
				$ret[] = $rec;
			}
		}
		$model->data = $ret;
	}
}


class DeleteSqlCmd implements ISqlCmd{
	public function sql(Model $model) {
		if (!$model->getFilter()) throw new ModelException('Filter must be defined for DELETE operation.');
		
		$where = $model->getFilter()->sql($model, null);
		$sql = 'DELETE FROM ' . $model->getDb()->quot($model->table);
		if ($where) $sql .= ' WHERE '.$where; 
		return $sql.';';
	} 
	
	public function exec(Model $model, $async = false) {
		$res = array(); 
		$model->db->getQueryArray($this->sql($model), $async, $res);
		$model->data = array();
		foreach ($res as $row) {
			$rec = array();
			foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
			$model->data[] = $rec;
		}
	}
}

class SelectSqlCmd implements ISqlCmd {
	public function sql(Model $model) {
		if (!$model->getFilter()) throw new ModelException('Filter must be defined for SELECT operation.');
		$where = $model->getFilter()->sql($model, null);
		 
		$fields = array();
		foreach ($model->getFields() as $field) {
			$fields[] = $model->getDb()->quot($field->name);
		}

		$distinct = ($model->distinct && $model->getField($model->distinct)) ? 'DISTINCT ON("'.$model->distinct.'") ' : '';
		
		$sql = 'SELECT '.$distinct. implode(', ', $fields) . " FROM " . $model->getDb()->quot($model->table);

		if ($where) $sql .= ' WHERE '.$where;
		
		$ords = array();
		foreach($model->orders as $order) {
			if ($order['flag'] == Field::ORDER_ASC) {
				$ords[] = $model->getDb()->quot($order['name']) . ' ASC';
			} elseif ($order['flag'] == Field::ORDER_DESC) {
				$ords[] = $model->getDb()->quot($order['name']) . ' DESC';
			}
		} 
		if (!empty($ords)) $sql .= ' ORDER BY ' . implode(', ', $ords);
		$sql .= ' LIMIT '.$model->_limit.' OFFSET '.$model->_offset;
		return $sql.';'; 
	}
	
	public function exec(Model $model, $async = false) {
		$res = array(); 
		$model->db->getQueryArray($this->sql($model), $async, $res);
		$model->data = array();
		foreach ($res as $row) {
			$rec = array();
			foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
			$model->data[] = $rec;
		}
	}
}

class ModelData implements arrayaccess, Iterator{
	private $position = 0;
	
	public $data = array();
	/**
	 * @var Field[]
	 */
	public $fields = array();
	public $table = null;

	public function __construct($table) {
		$this->table = $table;
	}
	
	final public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}
	
	final public function offsetGet($offset) {
		if(isset($this->data[$offset])) return new ModelDataWrapper($this, $offset);
		throw new ModelException('Invalid ModelData offset.');
	}
	
	final public function offsetSet($offset, $value) {
		if (!is_array($value)) $value = array($value);
		if (!isset($this->data[$offset])) $this->data[$offset] = array();
		
		foreach ($this->fields as $key => $field) {
			if (isset($value[$key])) $this->data[$offset][$key] = $field->rawvalue($value[$key]);
			else $this->data[$offset][$key] = null;
		}
	}
	
	final public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}
	
   	final public function current () { return new ModelDataWrapper($this, $this->position); }
	final public function key () { return $this->position; }
	final public function next () { ++$this->position; }
	final public function rewind () { $this->position = 0; }
	final public function valid () { return isset($this->data[$this->position]); }
	
	final public function __get($name) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$res = array();
		foreach ($this->data as $row) {
			$res[] = $this->fields[$name]->value($row[$name]);
		}
		return $res;
	}
	
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
	
	final public function __isset($name) {
		if (!isset($this->fields[$name])) return false;
		$field = $this->fields[$name]->name;
		foreach ($this->data as $row) if (isset($row[$field])) return true;
        return false;
    }

    final public function __unset($name) {
    	if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
        $field = $this->fields[$name]->name;
        foreach ($this->data as &$row) $row[$field] = null;
    }
    
    public function column($name, $func = null) {
    	if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		$res = array();
		foreach ($this->data as $row) {
			if ($func) $res[] = $func($this->fields[$name]->value($row[$this->fields[$name]->name]));
			else $res[] = $this->fields[$name]->value($row[$this->fields[$name]->name]);
		}
		return $res;
    }
    
    public function clear() {
    	$this->data = array();
    } 
    
    public function count() {
    	return count($this->data);
    }
    
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

class ModelDataWrapper {
	private $offset = 0;
	private $model = null;
	
	public function __construct(ModelData &$model, $offset) {
		$this->model = $model;
		$this->offset = $offset;
	}
	
	private function assertField($name) {
		if (!isset($this->model->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this->model));
	}
	
	public function __get($name) {
		$this->assertField($name);
		return $this->model->fields[$name]->value($this->model->data[$this->offset][$name]);
	}
	
	public function __set($name, $value) {
		$this->assertField($name);
		$this->model->data[$this->offset][$name] = $this->model->fields[$name]->rawvalue($value);
	}
	
	public function __isset($name) {
		try {
			$this->assertField($name);
		} catch (ModelException $e) { return false; }
		return isset($this->model->data[$this->offset][$name]);
	}

    public function __unset($name) {
    	$this->assertField($name);
    	unset($this->model->data[$this->offset][$name]);
    }
    
    public function all() {
    	return $this->model->data[$this->offset];
    }

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

class Model extends ModelData {
	public $db = null;
	/**
	 * @var ISqlCmd
	 */
	public $sqlCmd = null;
	public $orders = array();
	public $_limit = 2000;
	public $_offset = 0;
	public $distinct = null;

	/**
	 * @var OSCollectionRequest
	 */
	public $_filterOS = null;

	public $silentCatchDBExceptions = false;
	
	/**
	 * @var ISqlFilter
	 */
	public $filterObj = null;

	/**
	 * @var array[int]Argument
	 */
	public $args = array();

	public function __sleep() {
		return array('data', 'fields', 'table');
	}

	public function __construct($table, IDatabase $db) {
		$this->db = $db;
		parent::__construct($table);
	}
	
	public function __clone() {
		if (isset($this->sqlCmd))
			$this->sqlCmd = clone $this->sqlCmd;
		if (isset($this->filterObj))
			$this->filterObj = clone $this->filterObj;
	}
	
	public function setDb(IDatabase $db) {
		$this->db = $db;
	}
	
	public function getDb() { return $this->db; }
	public function getFields() { 
		$ret = array();
		foreach ($this->fields as $f) if ($f->definition($this->db, $this->table)) $ret[] = $f;

		return $ret;
	}

	public function getArgs() {
		return $this->args;
	}

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
	
	public function field($name, Field $field) {
		$this->fields[$name] = $field;
	}

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
	
	public function getFilter() { return $this->filterObj; }

	public function insert() { 
		$this->sqlCmd = new InsertSqlCmd();
		return $this; 
	}

	public function call($stProsName) {
		$reflectionObj = new ReflectionClass('CallSqlCmd');
		$this->sqlCmd = $reflectionObj->newInstanceArgs(func_get_args());
		return $this;
	}

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
	
	public function get($pkeyValues = null) { 
		$this->sqlCmd = new SelectSqlCmd();
		if ($pkeyValues !== null) {
			if (!is_array($pkeyValues)) $pkeyValues = array($pkeyValues);
			$this->filter(new PKeyValuesSqlFilter($pkeyValues));
		}
		$this->orders = array();
		return $this; 
	}

	public function distinct($field) {
		if($this->sqlCmd instanceof SelectSqlCmd) {
			$this->distinct  = $field;
		}
	}
	
	public function delete() { 
		$this->sqlCmd = new DeleteSqlCmd();
		return $this; 
	} 
	
	public function filter($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		if (!$this->filterObj) $this->filterObj = $filter;
		else $this->filterObj = new AndCompositeSqlFilter($this->filterObj, $filter);
		return $this;
	}
		
	public function filterSet($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		$this->filterObj = $filter;
		return $this;
	}
	
	public function exclude($filter) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using filter() op.");
		if (!$this->filterObj) $this->filterObj = $filter;
		else $this->filterObj = new AndCompositeSqlFilter($this->filterObj, new NotCompositeSqlFilter($filter));
		return $this;
	}
	
	public function getSql() {
		return $this->sqlCmd->sql($this);
	}
	
	public function reset() {
		$this->sqlCmd = null;
		$this->filterObj = null;
		$this->orders = array();
		$this->_limit = 2000;
		$this->_offset = 0;
	}
	public function exec($async = false) {
		if (!isset($this->sqlCmd)) throw new ModelException("SQL command must be defined before using exec().");
		$res = $this->sqlCmd->exec($this, $async);
		$this->reset();
		return $this;
	}
	
	public function lastSql() {
		return $this->db->getLastQuery();
	}
	
	public function order($name, $desc = false) {
		if (!isset($this->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this));
		if ($desc) $this->orders[] = array('name' => $this->fields[$name]->name, 'flag' => Field::ORDER_DESC);
		else $this->orders[] = array('name' => $this->fields[$name]->name, 'flag' => Field::ORDER_ASC);
		return $this;
	}
	
    /*
     * filter generation functions
     */
    public function filterAll() { return new AllSqlFilter(); }
    public function all() {return $this->filter(new AllSqlFilter());}
	public function filterFields() { return new FieldsSqlFilter(func_get_args()); }
	public function filterExpr() { return new FieldValueSqlFilter(); }
	
	public function definition() {
		$s = array();
		foreach ($this->fields as $field) {
			$s[] = $field->definition($this->db, $this->table);
		}
		$sql = 'CREATE TABLE ' . $this->db->quot($this->table) . '('.implode(', ', $s) . ') WITH (OIDS=FALSE);';
		return $sql;
	}
	
	public function row($fieldName, $value) {
		$field = $this->fields[$fieldName]; 
		foreach ($this->data as $key => $row) {
			/** @noinspection PhpUndefinedMethodInspection */
			if ($field->value($row[$field->name]) == $value) return $this[$key];
		}
		return false;
	}
	
	public function limit($limit) { 
		$this->_limit = $limit;
		return $this; 
	}
	
	public function offset($offset) { 
		$this->_offset = $offset;
		return $this; 
	}

	public function collection($fields = null) {
		$objArr = array();
		$obj = $this;
		$c = new OSCollection();
		$c->totalResults = count($this->data);
		if (isset($this->_filterOS) && isset($this->_filterOS->count) && isset($this->_filterOS->startIndex)) {
			$c->startIndex = $this->_filterOS->startIndex;
			$c->itemsPerPage = $this->_filterOS->count;
			$obj = $this->slice($c->startIndex, $c->startIndex + $c->itemsPerPage - 1);
		}

		foreach($obj as $row) {
			$data = $row->allTyped();
			if (is_array($fields) && !empty($fields)) {
				foreach($data as $key => $value) {
					if (!in_array($key, $fields)) unset($data[$key]);
				}
			}
			unset($data['password']);
			$objArr[] = (object) $data;
		}
		$c->setEntries($objArr);

		if (isset($this->_filterOS->filterBy)) $c->filtered = true;
		if (isset($this->_filterOS->sortBy)) $c->sorted = true;

		/**
		 * @todo: proper updated since value for collection
		 */
		$c->updatedSince = false;
		return $c;
	}

	public function filterOS(OSCollectionRequest $filter) {
		$this->_filterOS = $filter;
		$this->filter($filter);
		if ($filter->sortBy && isset($this->fields[$filter->sortBy])) {
			if ($filter->sortOrder == 'descending') $this->order($filter->sortBy, true);
			else $this->order($filter->sortBy);
		}
		return $this;
	}
}

class OSCollection implements ArrayAccess {
	public $startIndex = 0;
	public $itemsPerPage = null;
	public $totalResults = 0;
	protected $entry = array();
	public $filtered = false;
	public $sorted = false;
	public $updatedSince = false;

	public function setEntries($entries) {
		$this->entry = $entries;
	}

	public function offsetExists($offset) {
		return isset($this->entry[$offset]);
	}

	public function offsetGet($offset) {
		return $this->entry[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->entry[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->entry[$offset]);
	}

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

class OSCollectionRequest implements ISqlFilter {
	public $count = null;
	public $filterBy = null;
	public $filterOp = null;
	public $filterValue = null;
	public $sortBy = null;
	public $sortOrder = null;
	public $startIndex = 0;

	public function __construct($request = null) {
		if (is_array($request)) {
			$params = get_object_vars($this);
			foreach($params as $key => $val) {
				if (isset($request[$key])) $this->$key = $request[$key];
			}
		}
	}

	public function sql(Model $model, $row = null) {
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