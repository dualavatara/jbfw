<?php

namespace Admin\Extension\Template;

class TemplateExtension implements \Admin\ExtensionInterface {
	
	public function register(\Admin\Application $app) {
		$app['template'] = $app->share(function(\Admin\Application $app) {
			$config = $app->getConfig();
			$template = new TemplateEngine($app, $config['template.options']);
			
			return $template;
		});
	}
} 