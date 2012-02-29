<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:50
 */

namespace ctl;

require_once("model/PriceModel.php");
require_once("model/CurrencyModel.php");

class Price extends \Admin\Controller{
	private $data = array(
		'menu' => 'modules',
		'section' => 'price_list'
	);

	public function __construct(\Admin\Application $app) {
		parent::__construct($app);
		$cur = new CurrencyModel($this->app['db']);
		$cur->getAll();
		;
		foreach($cur as $row) {
			$this->data['currencies'][$row->id] = $row->name;
		}
	}

	public function do_add() {
		return $this->app['template']->render('Price\FormTemplate', $this->data);
	}

	public function do_delete(\Admin\Request $request) {
		$id = $request['id'];

		$model = new PriceModel($this->app['db']);
		$model->delById($id);

		$url = $this->app->getUrl('price_list');
		return $this->app->redirect($url);
	}

	public function do_edit(\Admin\Request $request) {
		$id = $request['id'];

		$model = new PriceModel($this->app['db']);
		if (!$model->getById($id)) {
			return $this->app->error404();
		}
		$this->data['model'] = $model[0];

		return $this->app['template']->render('Price\FormTemplate', $this->data);
	}

	public function do_list() {
		$model = new PriceModel($this->app['db']);
		$model->getAll();

		$this->data['model'] = $model;

		return $this->app['template']->render('Price\ListTemplate', $this->data);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
//		var_dump($form); return;

		$model = new PriceModel($this->app['db']);

		if ($form['id']) $model->saveFromForm($form);
		else $model->addFromForm($form);

		$url = $this->app->getUrl('price_list');
		return $this->app->redirect($url);
	}
}
