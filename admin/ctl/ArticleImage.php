<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class ArticleImage extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'articleimage_list', 'ArticleImage', $app);
	}

}