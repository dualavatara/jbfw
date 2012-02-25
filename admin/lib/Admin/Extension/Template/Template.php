<?php

namespace Admin\Extension\Template;

use \Admin\Extension\Template\Escaper\HtmlEscaper;

abstract class Template {
	
	/**
	 * @var \Admin\Application
	 */
	protected $app;
	private $parent;

	public function __construct(\Admin\Application $app) {
		$this->app = $app;
	}
	
	protected function setParent($parent) {
		$this->parent = $parent;
	}
	
	final public function render($data, $content = null) {
		$escaper = new HtmlEscaper();
		$data = $escaper->escape($data);
		
		ob_start();
		$this->show($data, $content);
		$content = ob_get_clean();
		
		if ($this->parent) {
			return $this->app['template']->render($this->parent, $data, $content);
		}
					
		return $content;
	}
	
	abstract protected function show($data, $content = null);
	
	final public function getUrl($routeName, $params = array()) {
		return $this->app->getUrl($routeName, $params);
	}

	final public function insertTemplate($templateClass, $data = array()) {
		echo $this->app['template']->render($templateClass, $data);
	}

    public function showLink($name, $routeName, $params = array(), $attribute='') {
	    // TODO: Security extension may be not registered
       if($this->app['user']->checkRoute($routeName)){
            if($url = $this->getUrl($routeName, $params)){
                echo '<a href="'.$url.'" '.$attribute.'>'.$name.'</a>';
            }
        }
    }
}