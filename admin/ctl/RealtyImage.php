<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class RealtyImage extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'realtyimage_list', 'RealtyImage', $app);
	}

}