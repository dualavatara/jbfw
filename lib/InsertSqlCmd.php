<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:31
 */

require_once("lib/ISqlCmd.php");

class InsertSqlCmd implements ISqlCmd {
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
			$id = $model->db->getLastInsertId();
			$model->db->getQueryArray("SELECT * FROM " . $model->db->quot($model->table) . " WHERE id = '" . $id . "'", $async, $res);
			foreach ($res as $row) {
				$rec = array();
				foreach($model->fields as $key => $field) $rec[$key] = $row[$field->name];
				$ret[] = $rec;
			}
		}
		$model->data = $ret;
	}
}
