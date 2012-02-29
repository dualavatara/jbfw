<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 20:53
 */

require_once 'admin/lib/IAdminModel.php';

abstract class AdminModel implements IAdminModel {
	/**
	 * @var Model
	 */
	private $model;

	/**
	 * @param Model $model
	 */
	public function __construct(Model $model) {
		$this->model = $model;
	}


	/**
	 * Select all object`s rows from database
	 */
	public function getAll() {
		$this->model->get()->all()->exec();
	}

	/**
	 * Adds new object record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function addFromForm($form) {
		$this->model->clear();
		$this->model[0] = $form;
		unset($this->model->data[0]['id']);
		$this->model->insert()->exec();
	}

	/**
	 * Selects object by id
	 * @param $id
	 * @return mixed	array if found, otherwise false
	 */
	public function getById($id) {
		$this->model->get($id)->exec();
		if ($this->model->count()) return $this[0]->all();
		return false;
	}

	/**
	 * Saves single object from form array as array('field' => 'value', ...)
	 * $form['id'] is required
	 * @param array $form
	 */
	public function saveFromForm($form) {
		if (isset($form['id'])){
			$this->model->clear();
			$this[0] = $form;
			$this->model->update()->exec();
		}
	}

	/**
	 * Deletes object by id
	 * @param $id
	 */
	public function delById($id) {
		$this->model->get($id)->delete()->exec();
	}

	/**
	 * @return \Model
	 */
	public function getModel() {
		return $this->model;
	}
}
