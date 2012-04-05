<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class CarImage extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'carimage_list', 'CarImage', $app);
	}

}