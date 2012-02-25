<?
interface ISqlFilter {
	public function sql(Model $model, $row);
}

class SqlFilterException extends Exception { }

class AllSqlFilter implements ISqlFilter {
	public function sql(Model $model, $row = null) { return '(true)'; }
}

class FieldsSqlFilter implements ISqlFilter {
	private $fields = null;
	public function __construct($fields) {
		$this->fields = $fields;
	}

	public function sql(Model $model, $row = null) {
		$data = $model->getData();
		$sql = '';
		$ops = array();
		if (isset($row)) {
			foreach ($this->fields as $field) {
				/** @noinspection PhpUndefinedMethodInspection */
				$s = $model->fields[$field]->sqlFilter($row[$field], $model->getDb());
				if ($s) $ops[] = $s;
			}
		} else {
			foreach ($this->fields as $field) {
				/** @noinspection PhpUndefinedMethodInspection */
				$s = $model->fields[$field]->sqlFilter($model->$field, $model->getDb());
				if ($s) $ops[] = $s;
			}
		}
		if (empty($ops)) return 'false';
		$sql = '(' . implode(' AND ', $ops) . ')';
		return $sql;
	}
}

class FieldValueOp {
	public $fieldName = null;
	public $value = null;
	public $op = null;
	public $logOp = null;
	public function __construct($fieldName, $value, $op, $logOp) {
		$this->fieldName = $fieldName;
		$this->value = $value;
		$this->op = $op;
		$this->logOp = $logOp;
	}
}

class FieldValueSqlFilter implements ISqlFilter {
	/**
	 * @var FieldValueOp[]
	 */
	private $fvos = array();
	
	/**
	 * @return FieldValueSqlFilter
	 */
	public function eq($fieldName, $value) { return $this->entry($fieldName, $value, '='); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function notEq($fieldName, $value) {	return $this->entry($fieldName, $value, '!='); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function more($fieldName, $value) {	return $this->entry($fieldName, $value, '>'); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function moreEq($fieldName, $value) {	return $this->entry($fieldName, $value, '>='); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function less($fieldName, $value) {	return $this->entry($fieldName, $value, '<'); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function lessEq($fieldName, $value) {	return $this->entry($fieldName, $value, '<='); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function like($fieldName, $value) {	return $this->entry($fieldName, $value, 'LIKE'); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function ilike($fieldName, $value) {	return $this->entry($fieldName, $value, 'ILIKE'); }
	
	/**
	 * @return FieldValueSqlFilter
	 */
	public function _and() { return $this->logOp('AND'); }
	/**
	 * @return FieldValueSqlFilter
	 */
	public function _or() {	return $this->logOp('OR'); }
	/**
	 * @return FieldValueSqlFilter
	 */
	private function logOp($op) {
		$last = array_pop($this->fvos);
		if ($last !== null) { 
			$last->logOp |= $op;
			array_push($this->fvos, $last);
		}
		return $this;
	}
	
	public function isEmpty() { return empty($this->fvos);}

	/**
	 * @param $fieldName
	 * @param $value
	 * @param $op
	 * @param string $logOp
	 * @return FieldValueSqlFilter
	 */
	private function entry($fieldName, $value, $op, $logOp = '') {
		if (!empty($this->fvos) && !end($this->fvos)->logOp) throw new ModelException('Multiple entries without logOp.');
		$this->fvos[] = new FieldValueOp($fieldName, $value, $op, $logOp);
		return $this;
	}

	public function sql(Model $model, $row = null) {
		
		$data = $model->getData();
		$sql = '';
		$ops = array();
		foreach ($this->fvos as $fvo) {
			if (is_array($fvo->value)) {
				foreach ($fvo->value as &$v) /** @noinspection PhpUndefinedMethodInspection */
					$v = $model->fields[$fvo->fieldName]->value($v);
			} else /** @noinspection PhpUndefinedMethodInspection */
				$fvo->value = $model->fields[$fvo->fieldName]->value($fvo->value);
			/** @noinspection PhpUndefinedMethodInspection */
			$s = $model->fields[$fvo->fieldName]->sqlFilter($fvo->value, $model->getDb(), $fvo->op);
			if (!$s) $s = 'false';
			$entry = '('.$s.')';
			if ($fvo->logOp) $entry .= ' '.$fvo->logOp.' ';
			$ops[] = $entry;
		}
		if (empty($ops)) return ' true ';
		$sql = '(' . implode('', $ops) . ')';
		return $sql;
	}
}

class PKeyValuesSqlFilter implements ISqlFilter {
	private $values = array();
	public function __construct($pkeyValues) {
		$this->values = $pkeyValues;
	}
	
	public function sql(Model $model, $row = null) {
		$pkeys = $model->getPKey();
		if (!$pkeys) throw new ModelException('Model must have primary key to use PKeyValuesSqlFilter.');
		if(empty($this->values)) return '(false)';
		$sqls = array();
		foreach($pkeys as $pkey) /** @noinspection PhpUndefinedMethodInspection */
			$sqls[] = $pkey->sqlFilter($this->values, $model->getDb());
		$sql = '(' . implode(' AND ', $sqls) . ')';
		return $sql;
	}
}

class PKeySqlFilter implements ISqlFilter {
	public function sql(Model $model, $row) {
		$pkeys = $model->getPKey();
		if (!$pkeys) throw new ModelException('Model must have primary key to use PKeySqlFilter.');

		$sqls = array();
		foreach($pkeys as $fieldName => $pkey) { /** @noinspection PhpUndefinedMethodInspection */
			$sqls[] = $pkey->sqlFilter($row[$fieldName], $model->getDb());
		}
		$sql = '(' . implode(' AND ', $sqls) . ')';
		return $sql;
	}
}

class AndCompositeSqlFilter implements ISqlFilter {
	/**
	 * @var ISqlFilter
	 */
	private $filter1 = null;
	
	/**
	 * @var ISqlFilter
	 */
	private $filter2 = null;
	
	public function __construct($filter1, $filter2) {
		$this->filter1 = $filter1;
		$this->filter2 = $filter2;
	}
	
	public function __clone() {
		$this->filter1 = clone $this->filter1;
		$this->filter2 = clone $this->filter2;
	}
	
	public function sql(Model $model, $row) {
		if (!$this->filter1 || !$this->filter2) throw new SqlFilterException('Invalid NOT filter argument.');
		return '(' . $this->filter1->sql($model, $row) . ') AND (' . $this->filter2->sql($model, $row) . ')';
	}
}

class OrCompositeSqlFilter implements ISqlFilter {
	/**
	 * @var ISqlFilter
	 */
	private $filter1 = null;
	
	/**
	 * @var ISqlFilter
	 */
	private $filter2 = null;
	
	public function __construct($filter1, $filter2) {
		$this->filter1 = $filter1;
		$this->filter2 = $filter2;
	}
	
	public function __clone() {
		$this->filter1 = clone $this->filter1;
		$this->filter2 = clone $this->filter2;	
	}
	
	public function sql(Model $model, $row) {
		if (!$this->filter1 || !$this->filter2) throw new SqlFilterException('Invalid NOT filter argument.');
		return '(' . $this->filter1->sql($model, $row) . ') OR (' . $this->filter2->sql($model, $row) . ')';
	}
}

class NotCompositeSqlFilter implements ISqlFilter {
	/**
	 * @var ISqlFilter
	 */
	private $filter = null;
	
	public function __construct($filter) {
		$this->filter = $filter;
	}
	
	public function __clone() {
		$this->filter = clone $this->filter;
	}
	
	public function sql(Model $model, $row) {
		if (!$this->filter) throw new SqlFilterException('Invalid NOT filter argument.');	
		return 'NOT ('.$this->filter->sql($model, $row).')';
	}
}
?>