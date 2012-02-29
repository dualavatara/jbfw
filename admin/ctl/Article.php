<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:50
 */

namespace ctl;

require_once 'admin/lib/StdController.php';

class Article extends \Admin\StdController {

	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'article_list', 'Article', $app);

		$this->data['types'] = $this->model->getModel()->getTypes();
	}
}
