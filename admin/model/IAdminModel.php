<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 20:36
 */
interface IAdminModel {
	/**
	 * Select all object`s rows from database
	 */
	public function getAll();

	/**
	 * Adds new object record into database
	 * @param string $name
	 * @param string $sign
	 * @param float $value
	 */
	public function addFromForm($form);

	/**
	 * Selects object by id
	 * @param $id
	 * @return mixed	array if found, otherwise false
	 */
	public function getById($id);

	/**
	 * Saves single object from form array as array('field' => 'value', ...)
	 * $form['id'] is required
	 * @param array $form
	 */
	public function saveFromForm($form);

	/**
	 * Deletes object by id
	 * @param $id
	 */
	public function delById($id);
}
