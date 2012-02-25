<?
interface ISessionStorage {
	/**
	 * Saves session value
	 * @abstract
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function save($key, $value);

	/**
	 * Loads session value
	 * @abstract
	 * @param $key
	 * @return mixed
	 */
	public function load($key);

	/**
	 * Check if value exists in session
	 * @abstract
	 * @param string $key
	 * @return bool
	 */
	public function exists($key);
}

interface IPrintable {

	/**
	 * @abstract
	 * @return String
	 */
	public function toString();
}

interface IUserContainer {
	public function getUser();
	public function setUser($user);
	public function save();
}
?>