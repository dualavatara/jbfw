<?
//lib classes
require_once('lib/requesthandler.web.lib.php');
require_once('lib/dispatcher.lib.php');
require_once('lib/session.lib.php');

//ctl classes

//model classes


class DIContainer extends Singletone{

	public function __construct() {}
	
	public function getUrl() {
		if ($_SERVER["SERVER_PORT"] == HTTPS_PORT) $proto = 'https';
		else $proto = 'http';
		return $proto . '://' . $_SERVER['HTTP_HOST'] . $_SERVER["DOCUMENT_URI"];
	}

	public function Language() {
		if (!Language::instantiated()) {
			$isMulti = !defined('MULTI_LANG') || !MULTI_LANG;
			Language::obj()->init($isMulti, $this->Cache(CACHE_CLASS), new PhraseModel($this->PGDatabase()));
		}
		return Language::obj();
	}

	public function WebRequestHandler(IDispatcher $dispatcher) {
		return new WebRequestHandler($dispatcher);
	}

	public function BaseEntryHandler($handler) {
		return new BaseEntryHandler($handler);
	}

	public function DataStorageMedia() {
		require_once ('lib/datastorage.media.lib.php');
		return new DataStorageMedia('.' . PATH_DATA);
	}


	//Controllers *********************************************************************************************** //

	// Views **************************************************************************************************** //


	// Request matchers ********************************************************************************************* //

	public function WebRequestMatcher($key, $class, $method, $authorisationRequired = true) {
		return new WebRequestMatcher($key, $class, $method, $authorisationRequired);
	}


	// Models ******************************************************************************************************* //

	/**
	 * @return AccountModel
	 */
//	public function AccountModel() {
//		return new AccountModel($this->PGDatabase());
//	}




	// Misc ********************************************************************************************************* //

	/**
	 * @param string $class
	 * @return ICache
	 */
	public function Cache($class) {
		$class = class_exists($class) ? $class : 'MBCache';
		return new $class();
	}

	public function PGDatabase() {
		if (isset($GLOBALS['DB_HOST']) && isset($GLOBALS['DB_DBNAME'])) return new PGDatabase($GLOBALS['DB_HOST'],$GLOBALS['DB_PORT'],$GLOBALS['DB_USER'],$GLOBALS['DB_PASSWD'],$GLOBALS['DB_DBNAME'],'utf-8');
		else return new PGDatabase(DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME, CHARSET_DB);
	}

	public function Session() {
		return Session::obj(new CacheSessionStorage($this->Cache(CACHE_CLASS)), $this->Generic());
	}

	public function Dispatcher() {
		return new Dispatcher($this);
	}

	public function __call($name, $arguments) {
		if (!method_exists('DIContainer', $name))
			throw new FunzayLogicException("Undefined DIContainer method call: " . $name);
		
		return call_user_func_array(array('DIContainer', $name), $arguments);
    }


	/**
	 * @return OSCollectionRequest
	 */
	public function OSCollectionRequest() {
		return new OSCollectionRequest();
	}
}

?>
