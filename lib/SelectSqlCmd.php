<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:33
 */

require_once("lib/ISqlCmd.php");

class SelectSqlCmd implements ISqlCmd {
	public function sql(Model $model) {
		if (!$model->getFilter()) throw new ModelException('Filter must be defined for SELECT operation.');
		$where = $model->getFilter()->sql($model, null);

		$fields = array();
		foreach ($model->getFields() as $field) {
            if ($model->fUseInQuery && !$field->fInQuery) continue;
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
