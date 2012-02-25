<?php

interface IDataStorage {
	
	/**
	 * If specified key already exists resource data will be overwritten
	 * otherwise new resource will be created.
	 * 
	 * @abstract
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function write($key, $value);
	
	/**
	 * Returns resource data from storage.
	 * 
	 * @abstract
	 * @param string $key
	 * @return string
	 */
	public function read($key);
	
	/**
	 * Removes resource from storage.
	 * 
	 * @abstract
	 * @param string $key
	 * @return void
	 */
	public function remove($key);
	
	/**
	 * Gets meta info about resource in storage.
	 * 
	 * @abstract
	 * @param string $key
	 * @return array
	 */
	public function getMeta($key);
	
	/**
	 * Writes resource data into output buffer. 
	 * 
	 * @abstract
	 * @param string $key
	 * @return void
	 */
	public function output($key);
}