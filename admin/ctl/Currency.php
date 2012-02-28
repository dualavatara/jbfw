<?php

require_once 'model/CurrencyModel.php';

class Currency extends Admin\Controller {

	private $data = array('menu'    => 'modules',
						  'section' => 'currency_list');

	public function do_add() {
		return $this->app['template']->render('Currency\FormTemplate', $this->data);
	}

	public function do_delete(\Admin\Request $request) {
		$id = $request['id'];

		$model = new CurrencyModel($this->app['db']);
		$model->delById($id);

		$url = $this->app->getUrl('currency_list');
		return $this->app->redirect($url);
	}

	public function do_edit(\Admin\Request $request) {
		$id = $request['id'];

		$model = new CurrencyModel($this->app['db']);
		if (!$model->getById($id)) {
			return $this->app->error404();
		}
		$this->data['model'] = $model[0];

		return $this->app['template']->render('Currency\FormTemplate', $this->data);
	}

	public function do_list() {
		$model = new CurrencyModel($this->app['db']);
		$model->getAll();

		$this->data['model'] = $model;

		return $this->app['template']->render('Currency\ListTemplate', $this->data);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
		if (count($form['routes'])) {
			$routes = array_keys($form['routes']);
			unset($form['routes']);
		} else $routes = array();

		$model = new CurrencyModel($this->app['db']);

		if ($form['id']) $model->saveFromForm($form);
		else $model->addFromForm($form);

		$url = $this->app->getUrl('currency_list');
		return $this->app->redirect($url);
	}
}