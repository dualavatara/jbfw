<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:32
 */

require_once("lib/ISqlCmd.php");

class UpdateSqlCmd implements ISqlCmd {
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
		return $sql;
	}

	public function exec(Model $model, $async = false) {
		foreach ($model->data as $record) {
			$res = array();
			try {
				$model->db->getQueryArray($this->updateSql($model, $record), $async, $res);
			} catch (Exception $e) {
				if ($model->silentCatchDBExceptions) continue;
				throw $e;
			}
		}
	}
}
