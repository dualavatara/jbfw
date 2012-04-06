<?php
namespace ctl;

require_once 'model/CustomerModel.php';
require_once 'admin/lib/StdController.php';

class CarRentOffice extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'carrentoffice_list', 'CarRentOffice', $app);

		$cm = new \CustomerModel($this->app['db']);
		$cm->get()->all()->exec();

		foreach($cm as $row) {
			$this->data['customers'][$row->id] = $row->name;
		}
	}

	public function do_json(\Admin\Request $request) {
		$name = $request['name'];
		$filter = $this->model->getModel()->filterExpr();
		if (isset($request['id'])) $filter->eq('id', $request['id']);
		else if ($name) $filter->like('name', "%$name%");
		else $filter = new \AllSqlFilter();
		$this->model->getModel()->get()->filter($filter)->exec();
		$data = array();
		foreach($this->model->getModel() as $row) $data[$row->id] = $row->name;
		return json_encode($this->model->getModel()->data);
	}
}