<?
require_once('lib/exception.lib.php');
require_once('lib/dicontainer.lib.php');
require_once('lib/abstract.lib.php');

require_once 'lib/Session.php';

interface IDispatcher {

	/**
	 * @abstract
	 * @return array
	 */
	public function getRequest();

	/**
	 * @abstract
	 * @return DIContainer
	 */
	public function di();

	/**
	 * @return ISession
	 */
	public function getSession();

	/**
	 * @param \ISession $session
	 */
	public function setSession(ISession $session);

	/**
	 * @abstract
	 * @param string $url    URL for redirect to
	 */
	public function redirect($url);

	/**
	 * @abstract
	 * @return string
	 */
	public function getReferer();
}

class Dispatcher implements IDispatcher {

	/**
	 * @var array
	 */
	private $classes;

	/**
	 * @var DIContainer
	 */
	private $di;

	/**
	 * @var \ISession
	 */
	private $session;


	/**
	 * @param ISession $session
	 * @param DIContainer $di
	 */
	public function __construct(DIContainer $di) {
		$this->di = $di;

		if (!Session::obj()->lang) Session::obj()->lang = DEFAULT_LANG;
		if (!Session::obj()->currency) Session::obj()->currency = DEFAULT_CURRENCY;
		$this->classes = array(
			$this->di->WebRequestHandler($this)
		);
	}

	/**
	 * @throws FunzayAPIException
	 * @return void
	 */
	public function main() {
		$handled = false;

		foreach ($this->classes as $class) {
			$handled = $class->handle($_SERVER['REQUEST_URI']);
			if ($handled) break;
		}

		if (!$handled) {
			throw new NotFoundException();
		}
	}

	/**
	 * @return array
	 */
	public function getRequest() {
		return $_REQUEST;
	}

	/**
	 * @return DIContainer
	 */
	public function di() {
		return $this->di;
	}


	public function setDI($value) {
		$this->di = $value;
	}

	/**
	 * @return ISession
	 */
	public function getSession() {
		return $this->session;
	}

	public function killSession() {
		$this->session->clean();
		$this->oauth->delete();
	}

	/**
	 * @param \ISession $session
	 */
	public function setSession(ISession $session) {
		$this->session = $session;
	}

	/**
	 */
	public function redirect($url) {
		header('Location: ' . $url);
	}

	/**
	 * @return string
	 */
	public function getReferer() {
		return $_SERVER['HTTP_REFERER'];
	}
}

?>