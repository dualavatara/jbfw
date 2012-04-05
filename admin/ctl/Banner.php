<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Banner extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'banner_list', 'Banner', $app);

		$this->data['types'] = $this->model->getModel()->getTypes();
	}

}