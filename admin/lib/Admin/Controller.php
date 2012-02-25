<?php

namespace Admin;

class Controller {
	
	/**
	 * @var \Admin\Application
	 */
	protected $app;
	
	public function __construct(\Admin\Application $app) {
		$this->app = $app;
	}
	
	function __invoke($request) {
		if (!isset($request['action'])) {
			throw new \InvalidArgumentException('Action parameter is not defined');
		}
		
		$action = $request['action'];
		
		$action = 'do_'.$action;
		if (!method_exists($this, $action)) {
			throw new \InvalidArgumentException('Action method not found in controller');
		}
		
		return $this->$action($request);
	}
}