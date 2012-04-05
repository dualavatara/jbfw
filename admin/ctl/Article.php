<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:50
 */

namespace ctl;

require_once 'admin/lib/StdController.php';

class Article extends \Admin\StdController {

	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'article_list', 'Article', $app);

		$this->data['types'] = $this->model->getModel()->getTypes();
	}
}
