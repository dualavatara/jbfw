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
		if ($data['model'] && isset($data['model']->noEscape) && $data['model']->noEscape) {}
		else $data = $escaper->escape($data);
		
		ob_start();
		$this->show($data, $content);
		$content = ob_get_clean();
		
		if ($this->parent) {
			return $this->app['template']->render($this->parent, $data, $content);
		}
					
		return $content;
	}
	
	abstract protected function show($data, $content = null);
	
	final public function getUrl($routeName, $params = array(), $noSessionParams = false) {
	//	if (isset($_SESSION['urlparams']) && !$noSessionParams) $params = array_merge($params, $_SESSION['urlparams']);
		return $this->app->getUrl($routeName, $params, true);
	}

	final public function insertTemplate($templateClass, $data = array()) {
		echo $this->app['template']->render($templateClass, $data);
	}

    public function showLink($name, $routeName, $params = array(), $attribute='', $noSessionParams = false) {
	    // TODO: Security extension may be not registered
       if($this->app['user']->checkRoute($routeName)){
            if($url = $this->getUrl($routeName, $params, $noSessionParams)){
                echo '<a href="'.$url.'" '.$attribute.'>'.$name.'</a>';
            }
        }
    }
	public function toParentLink() {
		if ($_REQUEST['from_route']) {
			echo '<a href="'.$_REQUEST['from_route'].'">[К родителю]</a>';
		}
	}
	public function listLink() {
		if ($_SESSION['listurl']) {
			echo '<a href="'.$_SESSION['listurl'].'">[Список]</a>';
		}
	}
}