<?
require_once('lib/requesthandler.lib.php');


class WebRequestHandler extends RequestHandler implements IRequestHandler {
	/**
	 * @param \IDispatcher $dispatcher
	 */
	public function __construct(IDispatcher $dispatcher) {
		parent::__construct($dispatcher);

//		$this->addMatcher($this->di->WebRequestMatcher('/^\/login$/', 'AuthFormCtl', 'login', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/$/', 'IndexCtl', 'main', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/lang$/', 'IndexCtl', 'setLang', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/currency$/', 'IndexCtl', 'setCurrency', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/s\/(?<key>.*)$/', 'StaticCtl', 'get', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/realty$/', 'RealtyCtl', 'index', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/realty\/profile\/(?<realtyId>.*)$/', 'RealtyCtl', 'profile', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/car$/', 'CarCtl', 'index', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/car\/profile\/(?<carId>.*)$/', 'CarCtl', 'profile', IRequestMatcher::NO_AUTH_REQUIRED));
	}

	/**
	 * @param string $requestUri
	 * @return bool
	 */
	public function handle($requestUri) {
		try {
			$path = parse_url($requestUri, PHP_URL_PATH);
			// TODO: Do not return false but show 404 error page
			return $this->callCtlMethod($path);
		}  catch (IHttpException $e) {
			header(sprintf('HTTP/1.1 %s %s', $e->getHttpCode(), $e->getHttpText()));
			// TODO: replace to ErrorView implementation
			error_log($e->getMessage());
			//$view = new ErrorsView();
			//$view->setErrors($e->getHttpCode());
			//$view->show();
		}
		
		return true;
	}
}
?>