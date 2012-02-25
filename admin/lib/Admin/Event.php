<?php

namespace Admin;

class Event {
	
	const REQUEST = 'request';
	
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var array
	 */
	private $data;
	
	/**
	 * @param string $name Event name
	 * @param array  $data Event data
	 */
	public function __construct($name, array $data = array()) {
		$this->name = $name;
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}