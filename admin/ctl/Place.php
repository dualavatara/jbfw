<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Place extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'place_list', 'Place', $app);
	}
	public function do_json(\Admin\Request $request) {
		$name = $request['name'];
		$resort_id = $request['resort_id'];
		$filter = $this->model->getModel()->filterExpr();
		if (isset($request['id'])) $filter->eq('id', $request['id']);
		else $filter->like('name', $name . '%');
		if ($resort_id) $filter->_and()->eq('resort_id', $resort_id);
		$this->model->getModel()->get()->filter($filter)->exec();
		$data = array();
		foreach($this->model->getModel() as $row) $data[$row->id] = $row->name . $resort_id;
		return json_encode($this->model->getModel()->data);
	}
}