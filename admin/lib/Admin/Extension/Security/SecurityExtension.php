<?php

namespace Admin\Extension\Security;

/**
 * Depends on Session extension.
 */
class SecurityExtension implements \Admin\ExtensionInterface {
	
	/**
	 * @var \Admin\Application
	 */
	private $app;
	
	public function register(\Admin\Application $app) {
		$config = $app->getConfig();
		$className = $config['security.options']->class;
		
		$user = new $className($app);
		if (!($user instanceof SecurityUserInterface)) {
			throw new \UnexpectedValueException($className . ' class is not implementing SecurityUserInterface.');
		}
		$app['user'] = $user;
		
		$app['dispatcher']->connect(\Admin\Event::REQUEST, array($this, 'onRequest'));
		
		$this->app = $app;
	}
	
	public function onRequest(\Admin\Event $event) {
		$config = $this->app->getConfig();
		$options = $config['security.options'];
		
		if (!isset($options['login_route']) || !isset($options['session_key'])) {
			throw new \InvalidArgumentException('Required options of SecurityExtension are not defined.');
		}
		
		$data = $event->getData();
		if (
			$data['route'] != $options['login_route'] && // If user is not on login page
			!$this->app['user']->isAuthenticated()       // and not logged in
		) {
			// Save requested page to redirect user back to it
			$this->app['session']->write('referrer', $data['url']); 
			// Redirect it to login page
			$login_url = $this->app->getUrl($options['login_route']);
			return $this->app->redirect($login_url);
		}
		
		if(!$this->app['user']->checkRoute($data['route'])) {
			return new \Admin\Response('', 403);
		}
		
		return null;
	}
} 