<?php
/**
 * User: dualavatara
 * Date: 3/19/12
 * Time: 11:04 PM
 */

require_once 'lib/ModelData.php';

/**
 *
 */
class ModelDataWrapper {
	/**
	 * @var int
	 */
	private $offset = 0;
	/**
	 * @var ModelData|null
	 */
	private $model = null;

	public function getOffset() { return $this->offset; }
	/**
	 * @param ModelData $model
	 * @param $offset
	 */
	public function __construct(ModelData &$model, $offset) {
		$this->model = $model;
		$this->offset = $offset;
	}

	/**
	 * @param $name
	 * @throws ModelException
	 */
	private function assertField($name) {
		if (!isset($this->model->fields[$name])) throw new ModelException($name.' is undefined in ' . get_class($this->model));
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		$this->assertField($name);
		return $this->model->fields[$name]->value($this->model->data[$this->offset][$name]);
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value) {
		$this->assertField($name);
		$this->model->data[$this->offset][$name] = $this->model->fields[$name]->rawvalue($value);
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function __isset($name) {
		try {
			$this->assertField($name);
		} catch (ModelException $e) { return false; }
		return isset($this->model->data[$this->offset][$name]);
	}

	/**
	 * @param $name
	 */
	public function __unset($name) {
		$this->assertField($name);
		unset($this->model->data[$this->offset][$name]);
	}

	/**
	 * @codeCoverageIgnore
	 * @return mixed
	 */
	public function all() {
		return $this->model->data[$this->offset];
	}

	/**
	 * @codeCoverageIgnore
	 * @return \ModelData|null
	 */
	public function getModel() {
		return $this->model;
	}

	public function __call($name, $arguments) {
		if (method_exists($this->model, $name)) {
			array_unshift($arguments, $this->offset);
			return call_user_func_array(array($this->model, $name), $arguments);
		} else throw new ModelException("Method $name is undefined for class " . get_class($this->model));
	}
}
