<?php

namespace Admin;

class RouteCollection implements \Iterator, \Countable {

	/**
	 * Associative array of routes.
	 * Key is route name (next available number if not defined), and value is also array with structure: 
	 * [0] - route
	 * [1] - callback
	 * 
	 * @var array
	 */
	private $routes = array();
	
	/**
	 * @var int Counter to check is position in routes array valid.
	 */
	private $count = 0;

	/**
	 * Adds into collection pair of route and callback.
	 * You can optionally specify pair's name to address it in future.
	 * 
	 * @param \Admin\Route $route    Route
	 * @param mixed        $callback Callback for the specified route
	 * @param string|null  $name     Pair name (optional).
	 * 
	 * @return void
	 */
	public function add(Route $route, $callback, $name = null) {
		$item = array($route, $callback);
		if (null == $name) {
			$this->routes[] = $item;
		} else {
			$this->routes[$name] = $item;
		}
	}
	
	/**
	 * Gets route object for the specified name.
	 * 
	 * @param string $name Route name
	 * 
	 * @return \Admin\Route
	 */
	public function getRoute($name) {
		return $this->routes[$name][0];
	}
	
	/**
	 * Gets callback for the specified name.
	 * 
	 * @param string $name Route name
	 * 
	 * @return mixed
	 */
	public function getCallback($name) {
		return $this->routes[$name][1];
	}

	/*
	 * Implementations of interfaces
	 */
	public function current() {
		$item = current($this->routes);
		return $item[0];
	}

	public function next() {
		next($this->routes);

		$this->count--;
	}

	public function key() {
		return key($this->routes);
	}

	public function valid() {
		return $this->count > 0;
	}

	public function rewind() {
		reset($this->routes);
		
		$this->count = count($this->routes);
	}

	public function count() {
		return count($this->routes);
	}
}