<?php

namespace ctl;

require_once 'admin/lib/StdController.php';

class Currency extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'currency_list', 'Currency', $app);
	}

	public function do_json(\Admin\Request $request) {
		$name = $request['name'];
		$filter = $this->model->getModel()->filterExpr();
		if (isset($request['id'])) $filter->eq('id', $request['id']);
		else $filter->like('name', $name . '%')->_or()->like('sign', $name . '%');
		$this->model->getModel()->get()->filter($filter)->exec();
		$data = array();
		foreach($this->model->getModel() as $row) $data[$row->id] = $row->name;
		return json_encode($this->model->getModel()->data);
	}
}