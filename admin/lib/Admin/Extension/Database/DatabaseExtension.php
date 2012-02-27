<?php

namespace Admin\Extension\Database;

/**
 * @todo Convert to Model extension
 * Autoload Model classes
 */
class DatabaseExtension implements \Admin\ExtensionInterface {
	
	public function register(\Admin\Application $app) {
		$app['db'] = $app->share(function(\Admin\Application $app) {
			$config = $app->getConfig();
			$options = $config['db.options'];
			
			$className = isset($options['class']) ? $options['class'] : '\Admin\Extension\Database\Database';
			
			$db = new $className(
//				$options['host'],
//				$options['port'],
//				$options['user'],
//				$options['pass'],
//				$options['name']
				$options['dsn'],
				$options['user'],
				$options['pass'],
				$options['charset']
			);
			
			if (!($db instanceof DatabaseInterface)) {
				throw new \UnexpectedValueException($className . ' class is not implementing DatabaseInterface.');
			}
			
			return $db;
		});
	}
} 