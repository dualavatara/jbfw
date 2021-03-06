<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Resort extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'resort_list', 'Resort', $app);
	}

	public function do_json(\Admin\Request $request) {
		$name = $request['name'];
		$filter = $this->model->getModel()->filterExpr();
		if (isset($request['id'])) $filter->eq('id', $request['id']);
		else $filter->like('name', $name . '%');
		$this->model->getModel()->get()->filter($filter)->order('name')->exec();
		$data = array();
		foreach($this->model->getModel() as $row) $data[$row->id] = $row->name;
		return json_encode($this->model->getModel()->data);
	}
}