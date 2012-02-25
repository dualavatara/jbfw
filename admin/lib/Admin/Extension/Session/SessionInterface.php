<?php

namespace Admin\Extension\Session;

interface SessionInterface {
	
	public function read($key, $default = null);
	
	public function remove($key);
	
	public function write($key, $value);
	
	public function start();
}