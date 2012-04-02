<?
class Singletone {
	protected function __construct() {}

	/**
	 * Create object in global scope if not exist and return it or just return it.
	 * @static
	 * @return static
	 */
	public static function obj() {
		$class = get_called_class();
		$varname = $class . '_Singletone_obj';
		if (!isset($GLOBALS[$varname])) {
			$GLOBALS[$varname] = new $class();
		}
		return $GLOBALS[$varname];
	}

	/**
	 * Returns true if this object exists in the global scope, or false else
	 * @static
	 * @return bool
	 */
	public static function instantiated() {
		$class = get_called_class();
		$varname = $class . '_Singletone_obj';
		return isset($GLOBALS[$varname]);
	}

	/**
	 * Removes object from global scope
	 * @static
	 * @return void
	 */
	public static function release() {
		$class = get_called_class();
		$varname = $class . '_Singletone_obj';
		unset($GLOBALS[$varname]);
	}
}
?>