<?php
require_once ('lib/abstract.lib.php');

interface IRequestHandler {
	/**
	 * @abstract
	 * @param string $requestUri
	 * @return bool
	 */
	public function handle($requestUri);
}

interface IRequestMatcher {

	const NO_AUTH_REQUIRED						= 0;
    const UID_AND_CONSUMER_KEY_ONLY_REQUIRED    = 1;
	const SESSION_SECURED_USER_REQUIRED			= 2;
	const OAUTH_SECURED_USER_REQUIRED			= 3;

	/**
	 * @abstract
	 * @return bool
	 */
	public function match($value);

	/**
	 * @abstract
	 * @param array $params
	 * @return void
	 */
	public function call(IDispatcher $dispatcher);
}

abstract class RequestMatcher implements IRequestMatcher{

	/*
	 * @var array
	 * Base credentals, that necessary for system
	 * there are two fields there: 'consumer_key' and 'uid'
	 */
	protected $baseCredentals;

	protected $userOptions;

	/*
	 * @var ISession
	 */
	protected $session;

	/*
	 * @var IUserContainer
	 */
	protected $userContainer;

	/**
	 * @var bool
	 */
	protected $authorisationRequired;
	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * Parameters from match() call
	 * @var array
	 */
	protected $params = array();

	protected  $authController = array(
		self::NO_AUTH_REQUIRED						=> 'return true;',
		self::UID_AND_CONSUMER_KEY_ONLY_REQUIRED	=> 'checkUidAndConsumerKey',
		self::SESSION_SECURED_USER_REQUIRED			=> 'checkSession',
		self::OAUTH_SECURED_USER_REQUIRED			=> 'checkOAuth'
	);
	
	/**
	 * @param string $key
	 * @param string $class
	 * @param string $method
	 */
	public function __construct($key, $class, $method, $authorisationRequired = self::SESSION_SECURED_USER_REQUIRED) {
		$this->key = $key;
		$this->class = $class;
		$this->method = $method;
		$this->authorisationRequired = $authorisationRequired;
	}

	/**
	 * @param \IDispatcher  $dispatcher
	 *
	 * @return void
	 */
	public function call(IDispatcher $dispatcher) {

		if ($this->userOptions) {
			$this->relateUserToApplication($dispatcher->di());
		}

		$object = $dispatcher->di()->{$this->class}($dispatcher);
		return call_user_func_array(array($object, $this->method), $this->params);
	}

	private function relateUserToApplication(DIContainer $di) {
		$accModel = $di->AccountModel();
		$userApps = $accModel->getApplications($this->userOptions['id']);

		if (!in_array($this->token['oauth_consumer_key'], $userApps->app_key))
			$accModel->addApplication($this->userOptions['id'], $this->baseCredentals['consumer_key']);
	}

	protected function checkUidAndConsumerKey(DIContainer $di) {
		$params = array_merge($_REQUEST, OAuthRequest::parseAuthorizationHeader($_SERVER['HTTP_AUTHORIZATION']));
		$userparams = array();
		parse_str($params['params'], $userparams);
		Logger::obj()->debug($params);
		if (!$userparams['consumer_key']) {
			if (!$params['consumer_key'] && $params['oauth_consumer_key'])
				$params['consumer_key'] = $params['oauth_consumer_key'];
			elseif (!$params['consumer_key'] && $params['oauth_token']) {
				$oauth = $di->OAuthRequest();
				$token = $oauth->getToken();
				$params['consumer_key'] = $token['oauth_consumer_key'];
			}
			if ($params['consumer_key'])
				$userparams['consumer_key'] = $params['consumer_key'];

		}

		if (!$userparams['uid'] && $params['uid'])
			$userparams['uid'] = $params['uid'];

		if (!$userparams['uid'] || !$userparams['consumer_key'])
			return false;
		$this->baseCredentals = array(
			'uid'			=> $userparams['uid'],
			'consumer_key'	=> $userparams['consumer_key']
		);
		return true;
	}

	protected function checkSession(DIContainer $di) {
		$session = $di->Session();
		$session->start();


		if ($session->getUser() == NULL || $session->getConsumerKey() == NULL)
			return false;

		$this->session = $session;
		$this->userContainer = $session;
		$this->userOptions = $session->getUser();
		$this->baseCredentals = array(
			'uid'			=> $this->userOptions['uid'],
			'consumer_key'	=> $session->getConsumerKey()
		);
		return true;
	}

	protected function checkOAuth(DIContainer $di) {
		$oauth = $di->OAuthRequest();
		$token = $oauth->getToken();
		$user = $oauth->getUser();

		if (!$token || !$user) {
			$session = $GLOBALS['di']->Session();
			$session->start();

			$this->session = $session;
			if ($session->getOAuthToken() != NULL) {
				$oauth->load($session->getOAuthToken());
				if ($oauth->getToken())
					$token = $oauth->getToken();
				if ($oauth->getUser())
					$user = $oauth->getUser();
			}
		}

		if (!$token || !$user)
			return false;

		$this->userContainer = $oauth;
		$this->baseCredentals = array(
			'uid'			=> $user['uid'],
			'consumer_key'	=> $token['oauth_consumer_key']
		);

		$this->userOptions = $user;
		return true;
	}

	protected function checkAllAuth() {
		$throw = true;
		foreach($this->authController as $k => $v)
			if ($k >= $this->authorisationRequired) {
				if ($k == self::NO_AUTH_REQUIRED || $this->$v($GLOBALS['di'])) {
					$throw = false;
					break;
				}
			}
		if ($throw) throw new SecurityProtocolException();
	}

	protected function returnCred () {
		if ($this->userOptions)
			$res = array(
				'user'	=> $this->userOptions,
				'token'	=> array( 'oauth_consumer_key' => $this->baseCredentals['consumer_key'] )

			);
		else
			$res = array(
				'user'	=> array( 'uid' => $this->baseCredentals['uid']),
				'token' => array( 'oauth_consumer_key' => $this->baseCredentals['consumer_key'] )
			);
		if ($this->session)
			$res['session'] = $this->session;
		if ($this->userContainer)
			$res['userContainer'] = $this->userContainer;
		return $res;
	}
}

class RPCRequestMatcher extends RequestMatcher{
	public function match($value) {
		if ($this->key != $value )
			return false;
		$this->checkAllAuth();
		return $this->returnCred();
	}
}

class WebRequestMatcher extends RequestMatcher{
	public function match($value) {
		$found =  preg_match($this->key, $value, $m);
		if ($found) {
			foreach($m as $mk => $mv) if (is_string($mk)) $this->params[] = $mv;
			$this->checkAllAuth();
		}
		return ($found ? $this->returnCred() : false);
	}
}

abstract class RequestHandler {
	/**
	 * @var \IDispatcher
	 */
	protected $dispatcher;

    /**
	 * @var \DIContainer
	 */
	protected $di;

	/**
	 * @var \IViewFactory
	 */
	protected $viewFactory;

	/**
	 * @var IRequestMatcher[]
	 */
	protected $matchers = array();

	/**
	 * @param \IDispatcher $dispatcher
	 */
	public function __construct(IDispatcher $dispatcher) {
        $this->di = $dispatcher->di();
		$this->dispatcher = $dispatcher;
	}

	public function callCtlMethod($value) {
		$matched = false;
		foreach($this->matchers as $matcher) {
			if ($matched = $matcher->match($value)) {
				if ($matched['session'] && $matched['session'] instanceof ISession) {
					$this->dispatcher->setSession($matched['session']);
				}

				$view = $matcher->call($this->dispatcher);
				if ($view instanceof \View\IView) echo $view->show();
				break;
			}
		}
		return $matched;
	}

	/**
	 * @param IRequestMatcher $matcher
	 * @return void
	 */
	public function addMatcher(IRequestMatcher $matcher) {
		$this->matchers[] = $matcher;
	}
}
?>