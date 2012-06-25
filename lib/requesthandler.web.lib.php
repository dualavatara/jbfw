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
		$this->addMatcher($this->di->WebRequestMatcher('/^\/carorder\/(?<carId>.*)$/', 'CarCtl', 'order', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/carorder2\/(?<carId>.*)$/', 'CarCtl', 'order2', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/carorderfinish\/(?<carId>.*)$/', 'CarCtl', 'finish', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/car\/profile\/(?<carId>.*)$/', 'CarCtl', 'profile', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/article\/(?<id>.*)$/', 'ArticleCtl', 'article', IRequestMatcher::NO_AUTH_REQUIRED));
		$this->addMatcher($this->di->WebRequestMatcher('/^\/places\/(?<id>.*)$/', 'CarCtl', 'places', IRequestMatcher::NO_AUTH_REQUIRED));
	}

	/**
	 * @param string $requestUri
	 * @return bool
	 */
	public function handle($requestUri) {
		$path = parse_url($requestUri, PHP_URL_PATH);
		$res = $this->callCtlMethod($path);
		if (!$res) {
			$m = UrlAliases::obj()->get();
			$m->get()->filter($m->filterExpr()->eq('alias', $path))->exec();
			if ($m->count()) {
				$path = parse_url($m[0]->url, PHP_URL_PATH);
				$query = parse_url($m[0]->url, PHP_URL_QUERY);
				$r = array();
				parse_str($query, $r);
				$_REQUEST = array_merge($r, $_REQUEST);
				$res = $this->callCtlMethod($path);
			}
		}
		return $res;
	}
}
?>