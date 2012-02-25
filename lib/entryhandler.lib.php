<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g.sokolik
 * Date: 06.10.11
 * Time: 15:43
 */

require_once('lib/dicontainer.lib.php');

interface IEntryHeader {
	public function callHandler($dispatcher, $params);
}

class BaseEntryHandler implements IEntryHeader{
	/**
	 * @var string
	 */
	protected  $handler;

	/**
	 * @var DIContainer
	 */
	protected  $di;

	/**
	 * @param string $handler
	 * @param string $viewClass
	 */
	public function __construct($handler) {
		$this->di = new DIContainer();
		$this->handler = $handler;
	}

	/**
	 * @param IDispatcher $dispatcher
	 * @param array $params
	 * @return void
	 */
	public function callHandler($dispatcher, $params) {
		list($classname, $method) = explode('::', $this->handler);

		$object = $this->di->$classname($dispatcher);
		call_user_func_array(array($object, $method), $params);
	}
}
?>