<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:32
 */

require_once("lib/ISqlCmd.php");

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
