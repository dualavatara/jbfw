<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Banner extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('sys', 'banner_list', 'Banner', $app);

		$this->data['types'] = $this->model->getModel()->getTypes();
	}

}