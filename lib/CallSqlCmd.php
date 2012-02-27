<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:30
 */

require_once("lib/ISqlCmd.php");

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
