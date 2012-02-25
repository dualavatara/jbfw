<?php

namespace Admin\Extension\Session;

/**
 * Available options:
 * - [class = \Admin\Extension\Session\NativeSessionStorage] (Class name of session storage)
 */
class SessionExtension implements \Admin\ExtensionInterface {
	
	public function register(\Admin\Application $app) {
		$app['session'] = $app->share(function($app) {
			$config = $app->getConfig();
			$className = isset($config['session.options']) && isset($config['session.options']['class']) 
					? $config['session.options']['class']
					: '\Admin\Extension\Session\NativeSessionStorage';
			
			$sessionStorage = new $className();
			if (!($sessionStorage instanceof SessionInterface)) {
				throw new \UnexpectedValueException($className . ' class is not implementing SessionInterface.');
			}
			$sessionStorage->start();
				
			return $sessionStorage;
		});
	}
}