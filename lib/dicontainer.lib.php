<?
//lib classes
require_once('lib/requesthandler.web.lib.php');
require_once('lib/dispatcher.lib.php');
require_once 'lib/PDODatabase.php';

//model classes
require_once 'model/SettingModel.php';
require_once 'model/CurrencyModel.php';
require_once 'model/ArticleModel.php';
require_once 'model/BannerModel.php';
require_once 'model/RealtyModel.php';

//view classes
require_once 'view/TemplateView.php';

/**
 *
 */
class DIContainer extends Singletone{

	/**
	 *
	 */
	public function __construct() {}

	/**
	 * @return string
	 */
	public function getUrl() {
		if ($_SERVER["SERVER_PORT"] == HTTPS_PORT) $proto = 'https';
		else $proto = 'http';
		return $proto . '://' . $_SERVER['HTTP_HOST'] . $_SERVER["DOCUMENT_URI"];
	}

	/**
	 * @return mixed
	 */
	public function Language() {
		if (!Language::instantiated()) {
			$isMulti = !defined('MULTI_LANG') || !MULTI_LANG;
			Language::obj()->init($isMulti, $this->Cache(CACHE_CLASS), new PhraseModel($this->PGDatabase()));
		}
		return Language::obj();
	}

	/**
	 * @param IDispatcher $dispatcher
	 * @return WebRequestHandler
	 */
	public function WebRequestHandler(IDispatcher $dispatcher) {
		return new WebRequestHandler($dispatcher);
	}

	/**
	 * @param $handler
	 * @return BaseEntryHandler
	 */
	public function BaseEntryHandler($handler) {
		return new BaseEntryHandler($handler);
	}

	/**
	 * @return DataStorageMedia
	 */
	public function DataStorageMedia() {
		require_once ('lib/datastorage.media.lib.php');
		return new DataStorageMedia('.' . PATH_DATA);
	}


	//Controllers *********************************************************************************************** //

	/**
	 * @param $dispatcher
	 * @return \Ctl\IndexCtl
	 */
	public function IndexCtl($dispatcher) {
		return new \Ctl\IndexCtl($dispatcher);
	}

	/**
	 * @param $dispatcher
	 * @return \Ctl\StaticCtl
	 */
	public function StaticCtl($dispatcher) {
		return new \Ctl\StaticCtl($dispatcher);
	}

	/**
	 * @param $dispatcher
	 * @return Ctl\RealtyCtl
	 */
	public function RealtyCtl($dispatcher) {
		return new \Ctl\RealtyCtl($dispatcher);
	}

	/**
	 * @param $dispatcher
	 * @return Ctl\TemplateCtl
	 */
	public function TemplateCtl($dispatcher) {
		return new \Ctl\TemplateCtl($dispatcher);
	}

	public function SearchColumnCtl($dispatcher) {
		return new \Ctl\SearchColumnCtl($dispatcher);
	}
	// Views **************************************************************************************************** //

	/**
	 * @return View\TemplateView
	 */
	public function TemplateView(){
		return new \View\TemplateView();
	}

	/**
	 * @return View\IndexView
	 */
	public function IndexView() {
		return new \View\IndexView();
	}

	/**
	 * @return View\SearchColumnView
	 */
	public function SearchColumnView() {
		return new \View\SearchColumnView();
	}

	public function RealtyView() {
		return new \View\RealtyView();
	}

	// Request matchers ********************************************************************************************* //

	/**
	 * @param $key
	 * @param $class
	 * @param $method
	 * @param bool $authorisationRequired
	 * @return WebRequestMatcher
	 */
	public function WebRequestMatcher($key, $class, $method, $authorisationRequired = true) {
		return new WebRequestMatcher($key, $class, $method, $authorisationRequired);
	}


	// Models ******************************************************************************************************* //

	/**
	 * @return SettingModel
	 */
	public function SettingModel() {
		return new SettingModel($this->PDODatabase());
	}

	/**
	 * @return CurrencyModel
	 */
	public function CurrencyModel() {
		return new CurrencyModel($this->PDODatabase());
	}

	/**
	 * @return ArticleModel
	 */
	public function ArticleModel() {
		return new ArticleModel($this->PDODatabase());
	}

	/**
	 * @return BannerModel
	 */
	public function BannerModel() {
		return new BannerModel($this->PDODatabase());
	}

	/**
	 * @return RealtyModel
	 */
	public function RealtyModel() {
		return new RealtyModel($this->PDODatabase());
	}

	// Misc ********************************************************************************************************* //

	/**
	 * @param string $class
	 * @return ICache
	 */
	public function Cache($class) {
		$class = class_exists($class) ? $class : 'MBCache';
		return new $class();
	}

	/**
	 * @return PGDatabase
	 */
	public function PGDatabase() {
		if (isset($GLOBALS['DB_HOST']) && isset($GLOBALS['DB_DBNAME'])) return new PGDatabase($GLOBALS['DB_HOST'],$GLOBALS['DB_PORT'],$GLOBALS['DB_USER'],$GLOBALS['DB_PASSWD'],$GLOBALS['DB_DBNAME'],'utf-8');
		else return new PGDatabase(DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME, CHARSET_DB);
	}

	/**
	 * @return PDODatabase
	 */
	public function PDODatabase() {
		return new PDODatabase(DB_DSN, DB_USER, DB_PASS, DB_CHARSET);
	}

	/**
	 * @return Dispatcher
	 */
	public function Dispatcher() {
		return new Dispatcher($this);
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @throws FunzayLogicException
	 */
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
