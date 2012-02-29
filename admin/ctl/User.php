<?php

namespace ctl;

require_once 'model/adminuser.model.php';
require_once 'model/adminaccess.model.php';

class User extends \Admin\Controller {

	private $data = array(
		'menu' => 'sys',
		'section' => 'user_list'
	);

	public function do_add() {
		$this->data['routes'] = $this->getAllRoutes();
		$this->data['access'] = array();
		return $this->app['template']->render('User\FormTemplate', $this->data);
	}

	public function do_delete(\Admin\Request $request) {
		$id = $request['id'];

		$model = new \AdminUserModel($this->app['db']);
		$model->get($id)->delete()->exec();

        $model = new AdminAccessModel($this->app['db']);
        $filter = new FieldValueSqlFilter();
        $filter->eq('user_id', $id);
        $model->get()->filter($filter)->delete()->exec();

		$url = $this->app->getUrl('user_list');
		return $this->app->redirect($url);
	}

	public function do_edit(\Admin\Request $request) {
		$id = $request['id'];

		$model = new \AdminUserModel($this->app['db']);
		$model->get($id)->exec();
		if (0 == $model->count()) {
			return $this->app->error404();
		}
		$this->data['model'] = $model[0];
		$this->data['access'] = $this->app['user']->getRoutes($id);
		$this->data['routes'] = $this->getAllRoutes();

		return $this->app['template']->render('User\FormTemplate', $this->data);
	}

	public function do_list() {
		$model = new \AdminUserModel($this->app['db']);
		$model->get()->all()->exec();

		$this->data['model'] = $model;

		return $this->app['template']->render('User\ListTemplate', $this->data);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
        if(count($form['routes'])) {
            $routes = array_keys($form['routes']);
            unset($form['routes']);
        } else $routes = array();

		$form['password'] = ('' == $form['password']) ? null : md5($form['password']);

		$model = new \AdminUserModel($this->app['db']);
		$model[0] = $form;
		if ($form['id']) {
			$model->update()->exec();
		} else {
			unset($model[0]->id);
			$model[0]->created = DateTimeWithTZField::fromTimestamp(time());
			$model->insert()->exec();
		}
        if(isset($routes) && $model->count()) {
            $user_id = $model->data[0]['id'];
            $this->save_access($user_id, $routes);
        }

		$url = $this->app->getUrl('user_list');
		return $this->app->redirect($url);
	}

    public function save_access($user_id, $data) {
        $model = new AdminAccessModel($this->app['db']);
	    
	    // First delete all routes
        $filter = new FieldValueSqlFilter();
        $filter->eq('user_id', $user_id);
        $model->delete()->filter($filter)->exec();
	    
	    // Then insert only needed routes
	    // Default routes are excluded
        foreach($data as $index => $route_name) {
	        if (!in_array($route_name, $this->app['user']->getDefaultRoutes())) {
	            $model[$index] = array(
	                'user_id' => $user_id,
	                'route_name' => $route_name,
	            );
	        }
        }
	    $model->insert()->exec();
    }
	
	private function getAllRoutes() {
		// Group routes by controller's name and remove default routes
	    $routes = array();
	    foreach($this->app->getConfig()->routes as $route_name => $item) {
	        if (!in_array($route_name, $this->app['user']->getDefaultRoutes()))
	            $routes[$item[1]][$route_name] = $item[2]; // 1 - Controller name, 2 - action
	    }
		
		return $routes;
	}
}