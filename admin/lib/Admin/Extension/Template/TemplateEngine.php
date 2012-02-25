<?php
namespace Admin\Extension\Template;

class TemplateEngine {

	protected $app;
	
	public function __construct($app, $options) {
		if (!isset($options['path'])) {
			throw new \InvalidArgumentException('Path to template folder is not defined.');
		}
		
		$loader = new \Admin\ClassLoader();
		$loader->registerNamespace('\\', $options['path']);
		$loader->register();
		
		$this->app = $app;
	}

	/**
	 * Render template of specified class.
	 * Specified class must extend base \Admin\Extension\Template\Template class.
	 * All data that you pass to template through second parameter, will be
	 * automatically escaped (wrapped by corresponding Decorator).
	 * Path to template directory specified by 'path' parameter in configuration.
	 *
	 * @throws \UnexpectedValueException
	 *
	 * @param string      $templateClass Instance of this class will be
	 * @param array       $data          Parameters of template
	 * @param string|null $content       Content of nested template
	 *
	 * @return string
	 */
	public function render($templateClass, $data = array(), $content = null) {		
		$template = new $templateClass($this->app);
		if (!($template instanceof Template)) {
			throw new \UnexpectedValueException('Specified class is not template.');
		}
		
		return $template->render($data, $content);
	}
}
	