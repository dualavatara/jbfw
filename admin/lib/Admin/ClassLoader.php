<?php

namespace Admin;

/**
 * AutoLoader implementation for PHP 5.3.
 *
 * It is able to load classes that use namespace naming convention.
 *
 * Classes from a sub-namespace can be looked for in a list of locations
 * to ease the vendoring of a sub-set of classes for large projects.
 *
 * Example usage:
 * <code>
 *     $loader = new JBFWClassLoader();
 *
 *     // register classes with namespaces
 *     $loader->registerNamespaces('\Admin', _DIR__ . '/vendor');
 *     $loader->registerNamespaces('\\',     array(__DIR__ . '/modules', __DIR__ . '/lib'));
 *
 *     // activate the autoloader
 *     $loader->register();
 * </code>
 */ 
class ClassLoader {

	private $namespaces = array();

	/**
	 * Registers a namespace.
	 *
	 * @param string       $namespace The namespace
	 * @param array|string $paths     The location(s) of the namespace
	 */
	public function registerNamespace($namespace, $paths) {
		$this->namespaces[$namespace] = (array)$paths;
	}

	/**
	 * Registers this instance as an autoloader.
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Loads the given class or interface.
	 *
	 * @param string $class The name of the class
	 */
	private function loadClass($class) {
		if ($fileName = $this->resolveFile($class)) {
			require_once $fileName;
		}
	}

	/**
	 * Resolve the path to the file where the class is defined.
	 *
	 * @param string $class The name of the class
	 *
	 * @return string|false The path, if found
	 */
	public function resolveFile($class) {
		$class = ltrim($class, '\\');

		$namespace = '\\';
		$file = '';
		if (false !== $pos = strripos($class, '\\')) {
			$namespace .= substr($class, 0, $pos);
			$class     = substr($class, $pos + 1);
			$file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}

		$file .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

		foreach ($this->namespaces as $ns => $dirs) {
            if (0 !== strpos($namespace, $ns)) {
                continue;
            }
	
			foreach ($dirs as $dir) {
				$fileName = $dir . DIRECTORY_SEPARATOR . $file;
				if (is_file($fileName)) {
					return realpath($fileName);
				}
			}
		}

		return false;
	}
}