<?php

namespace Admin;

require_once 'ClassLoader.php';

$loader = new ClassLoader();
$loader->registerNamespace('\Admin', __DIR__ . '/..');
$loader->registerNamespace('\ctl', __DIR__ . '/../..');
$loader->registerNamespace('\model', __DIR__ . '/../..');
$loader->register();

class Application extends Container {

	/**
	 * @var \Admin\Config
	 */
	private $config;
	
	/**
	 * @var \Admin\RouteCollection
	 */
	private $routes;

	private $routesArr;

	/**
	 * Constructor.
	 * Creates default services, registers routes from configuration.
	 * There are no required parameters, but if 'baseUrl' is not defined,
	 * then it will be equaled to empty string.
	 * 
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		$this->config = new Config($config);
		
		if (isset($this->config['ctlPath'])) {
			$loader = new ClassLoader();
			$loader->registerNamespace('\\', array($this->config->ctlPath, __DIR__ . '/..'));
			$loader->register();
		}
		
		// Set default baseUrl to empty string
		if (!$this->config->offsetExists('baseUrl'))
			$this->config->baseUrl = '';
		
		if ($this->config->offsetExists('rootPath'))
			chdir($this->config->rootPath);

		$this->routes = new RouteCollection();
		$this->routesArr = array();
		if ($this->config->offsetExists('routes')) {

			foreach ($this->config->routes as $name => $params) {
				// add route name as first value in parameters array
				array_unshift($params, $name);
				$this->routesArr[$name] = $params[0];

				// To not pass all arguments to function manually
				call_user_func_array(array($this, 'registerController'), $params);
			}
		}
		
		$this['dispatcher'] = new EventDispatcher();
	}

	/**
	 * Maps path pattern to a callback.
	 *
	 * @param string   $path	 Path pattern
	 * @param callback $callback Callback of any valid type
	 *
	 * @return void
	 */
	public function registerRoute($path, $callback) {
		$this->routes->add(new Route($path), $callback);
	}

	public function findMenuBySection($sectionKey) {
		$ret = '';
		foreach ($this->config->menu as $name => $section) {
			foreach($section['sections'] as $entryKey => $entryVal) {
				if ($entryKey == $sectionKey) return $name;
			}
		}
		return null;
	}
	/**
	 * Maps path pattern to the specified controller and optionally action with certain name.
	 * 
	 * @see \Admin\Route
	 * 
	 * @throws \UnexpectedValueException If specified class does not extends base \Admin\Controller class
	 * 
	 * @param string      $name       Name of route in collection
	 * @param string      $path       Path pattern
	 * @param string      $controller Controller class name
	 * @param string|null $action     Action name
	 * 
	 * @return void
	 */
	public function registerController($name, $path, $controller, $action = null) {
		$path = $this->config->baseUrl . $path;
		
		$app = $this;
		
		$defaults = $action ? array('action' => $action) : array();
		$defaults['menu'] = $this->findMenuBySection($name);
		$route = new Route($path, $defaults);
		$callback = function($request) use ($app, $controller, $route) {
			$controller = '\\ctl\\' . $controller;
			$ctl = new $controller($app, $route);
			if (!($ctl instanceof Controller)) {
				throw new \UnexpectedValueException($controller . ' is not a controller');
			}
					
			return call_user_func($ctl, $request);
		};
		
		$this->routes->add($route, $callback, $name);
	}
	
	/**
	 * Handle the request and send response to output.
	 * 
	 * @return void
	 */
	public function run() {
		$response = $this->handle($_SERVER['REQUEST_URI']);
		$response->send();
	}
	
	/**
	 * Handle the specified URI
	 * 
	 * @param string $uri
	 * 
	 * @return Response
	 */
	public function handle($uri) {
		// Get only path part from URI
		$path = parse_url($uri, PHP_URL_PATH);
		
		foreach ($this->routes as $name => $route) {
			if (false !== ($params = $route->match($path))) {
				// Fire request event
				$event = new Event(
					Event::REQUEST,
					array('route' => $name, 'url' => $path)
				);
				$results = $this['dispatcher']->fire($event);
				
				// If response object has been returned from event listener than send it
				foreach ($results as $result) {
					if ($result instanceof \Admin\Response) {
						return $result;
					}
				}
				
				// Else run conroller
                $callback = $this->routes->getCallback($name);
                $request = Request::createFromGlobals($params);
                $response = call_user_func($callback, $request);
				
                // If not response object returned - try to convert
                if (!($response instanceof Response)) {
                    $response = new Response($response);
                }
				
				return $response;
			}
		}
		
		// Send 404 by default - if no matches with defined routes
		return new Response('', 404);
	}
	
	/**
	 * Returns application configuration.
	 * 
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}
	
	/**
	 * Creates path for the specified route with specified parameters.
	 * 
	 * @param string $routeName Route name
	 * @param array  $params    Route parameters
	 * 
	 * @return string 
	 */
	public function getUrl($routeName, $params = array(), $noSessionParams = false) {
		//if (isset($_SESSION['urlparams']) && !$noSessionParams) $params = array_merge($params, $_SESSION['urlparams']);
		$route = $this->routes->getRoute($routeName);

		return $route->getUrl($params);
	}

	/**
	 * Returns response that redirects user to the specified url.
	 * 
	 * @param string $url Absolute url to redirect to
	 * 
	 * @return Response
	 */
	public function redirect($url) {
		return new Response('', 302, array('Location' => $url));
	}

	/**
	 * Returns 404 error response.
	 * Used where specified route not found.
	 * 
	 * @return Response
	 */
	public function error404() {
		return new Response('', 404);
	}
	
	/**
	 * Registers extension and imports it's options into application config.
	 * 
	 * @param ExtensionInterface $extension
	 * @param array $options
	 * 
	 * @return void
	 */
	public function register(ExtensionInterface $extension, array $options = array()) {
		foreach ($options as $key => $value) {
			$this->config[$key] = $value;
		}
		
		$extension->register($this);
	}

	public function getRoutesArr() {
		return $this->routesArr;
	}
}