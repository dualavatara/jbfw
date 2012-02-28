<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 20:53
 */

require_once 'admin/model/IAdminModel.php';
require_once 'lib/model.lib.php';

abstract class AdminModel extends Model implements IAdminModel {
	/**
	 * Select all object`s rows from database
	 */
	public function getAll() {
		$this->get()->all()->exec();
	}

	/**
	 * Adds new object record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function addFromForm($form) {
		$this->clear();
		$this[0] = $form;
		unset($this->data[0]['id']);
		$this->insert()->exec();
	}

	/**
	 * Selects object by id
	 * @param $id
	 * @return mixed	array if found, otherwise false
	 */
	public function getById($id) {
		$this->get($id)->exec();
		if ($this->count()) return $this[0]->all();
		return false;
	}

	/**
	 * Saves single object from form array as array('field' => 'value', ...)
	 * $form['id'] is required
	 * @param array $form
	 */
	public function saveFromForm($form) {
		if (isset($form['id'])){
			$this->clear();
			$this[0] = $form;
			$this->update()->exec();
		}
	}

	/**
	 * Deletes object by id
	 * @param $id
	 */
	public function delById($id) {
		$this->get($id)->delete()->exec();
	}
}
