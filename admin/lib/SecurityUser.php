<?php

require_once 'model/adminuser.model.php';
require_once 'model/adminaccess.model.php';

class SecurityUser implements \Admin\Extension\Security\SecurityUserInterface {

    const ROUTES = 'routes';

	/**
	 * @var \AdminUserModel
	 */
	private $user;
	
	/**
	 * @var \Admin\Application
	 */
	private $app;
	
	/**
	 * @var string Session key to store information about user
	 */
	private $key;
	
	/**
	 * @var array Routes that are allowed always for any user
	 */
	private $defaultRoutes;
	
	public function __construct(\Admin\Application $app) {
		$config = $app->getConfig();

		$this->app = $app;
		$this->key = $config['security.options']->session_key;
		$this->defaultRoutes = array(
			$config['security.options']->login_route,
			$config['security.options']->logout_route,
		);
	}
	
	/**
	 * Validate user credentials and store user information in session.
	 * Return true if validation passed, false otherwise.
	 *
	 * @param string $login
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($login, $password) {
		// lazy model initialization
		if (null == $this->user)
			$this->user = new \AdminUserModel($this->app['db']);
		
		$this->user->get()->filter(
			$this->user->filterExpr()->
					eq('login', $login)->
					_and()->
					eq('password', md5($password))
		)->exec();
		
		// Save user info and allowed routes to session
		if (1 == $this->user->count()) {
			$this->app['session']->write($this->key, $this->user[0]);
            $routes = $this->getRoutes($this->user[0]->id);
            $this->app['session']->write(self::ROUTES, $routes);
			return true;
		}
		
		return false;
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout() {
		$this->app['session']->remove($this->key);
	}

	/**
	 * Checks if the user is authenticated or not.
	 *
	 * @return Boolean true if the user is authenticated, false otherwise
	 */
	public function isAuthenticated() {
		return  (null != $this->app['session']->read($this->key));
	}

	/**
	 * Return value of user model field.
	 *
	 * @throws \BadMethodCallException
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		$user = $this->app['session']->read($this->key);
		
		if (null == $user)
			throw new \BadMethodCallException('User is not authenticated');
		
		return $user->$name;
	}

	public function getDefaultRoutes() {
		return $this->defaultRoutes;
	}

	public function getRoutes($user_id = null) {
		if (null == $user_id) {
			return $this->isAuthenticated()
					? $this->app['session']->read(self::ROUTES)
					: $this->defaultRoutes;
		} else {
			$accessModel = new \AdminAccessModel($this->app['db']);
			$route_names = $accessModel->getRouteNames($user_id);
			return array_merge($route_names->route_name, $this->defaultRoutes);
		}
	}

	/**
	 * Checks that user can get access to specified route
	 * 
	 * @param string $name Route name
	 * 
	 * @return bool
	 */
	public function checkRoute($name) {
		return in_array($name, $this->getRoutes());
	}
}