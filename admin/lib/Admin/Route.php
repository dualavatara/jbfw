<?php

namespace Admin;

class Route {
	
	/**
	 * Placeholder format - "{placeholder_name}",
	 * where it's name can consist of letters, digits and "_" symbol.
	 */
	const PLACEHOLDER_PATTERN = '/\{([a-zA-Z0-9_]*)\}/';
	
	/**
	 * @var string URI path that have to be matched by route
	 */
	private $path;
	/**
	 * @var array Default values for parameter placeholders
	 */
	private $defaults;

	/**
	 * Defines path that will be matched by route and optionally default parameters values.
	 * Path can contains parameter placeholders in format /path/to/{placeholder_name}/
	 * that will be extracted from the matched path and returned as associative array,
	 * where key is placeholder name and value is matched part of path.
	 * Placeholder name can consist of letters, digits and "_" symbol.
	 * 
	 * @param string $path     Path part of URI to be matched (starts and ends with slash).
	 * @param array  $defaults Array of default parameters values.
	 */
	public function __construct($path, array $defaults = array()) {
		$this->path = $path;
		$this->defaults = $defaults;
	}

	/**
	 * Tests if specified path match current route.
	 * 
	 * @param  string $uri_path Path part of URI (starts and ends with slash).
	 * 
	 * @return array|bool Array of parameters if route match specified path, false otherwise.
	 */
	public function match($uri_path) {
		$arguments = array();

		$uri_pieces   = explode('/', $uri_path);
		$route_pieces = explode('/', $this->path);
		
		if (count($uri_pieces) != count($route_pieces)) {
			return false;
		}
		
		foreach ($route_pieces as $index => $route_part) {
			$uri_part = $uri_pieces[$index];
			
			if (preg_match(self::PLACEHOLDER_PATTERN, $route_part, $match)) {
				$arguments[$match[1]] = urldecode($uri_part);
			} elseif ($uri_part != $route_part) {
				return false;
			}
		}

		return $arguments + $this->defaults;
	}

	/**
	 * Generates path for current route with specified parameters.
	 * Parameters replace corresponding placeholders in route path.
	 * If no parameter is specified for placeholder then default value for parameter will be used.
	 * You can specify parameters which has no corresponding placeholders in path
	 * it will be placed as query string after path.
	 * All parameter values (include default values) will be encoded in
	 * {@link http://www.faqs.org/rfcs/rfc1738.html RFC 1738} format.
	 * 
	 * @param array $params Array of parameters that will replace corresponding parts of path.
	 * 
	 * @return string|false False if not all values for placeholders are defined
	 */
	public function getUrl(array $params = array()) {
		$url = $this->path;
		
		while (preg_match(self::PLACEHOLDER_PATTERN, $url, $match)) {
			$placeholder = $match[1];
			
			// define value to insert instead of placeholder
			if (isset($params[$placeholder])) {
				$value = $params[$placeholder];
				unset($params[$placeholder]);
			} elseif (isset($this->defaults[$placeholder])) {
				$value = $this->defaults[$placeholder];
			} else {
				return false;
			}
			
			$value = urlencode($value);
			$url = preg_replace(self::PLACEHOLDER_PATTERN, $value, $url, 1); // replace only first match
		}
		
		if (count($params) > 0) {
			$url .= '?' . http_build_query($params);
		}

		return $url;
	}
}