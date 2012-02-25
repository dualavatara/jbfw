<?php

require_once 'model/application.model.php';
require_once 'model/appmeta.model.php';
require_once 'lib/datastorage.file.lib.php';
 
class Codes extends Admin\Controller {

	private $data = array(
		'menu' => 'modules',
		'section' => 'codes_list'
	);

	public function do_list() {
		// Select application meta data
		$meta = new AppMetaModel($this->app['db']);
		$meta->get()->all()->exec();
		$this->data['meta'] = $meta;
		
		// Select involved applications info
		$appModel = new ApplicationModel($this->app['db']);
		$appModel->get($meta->app_id)->exec();
		$this->data['app'] = $appModel->count() != 0 
					? array_combine($appModel->consumer_key, $appModel->title) 
					: array();

		return $this->app['template']->render('Codes\ListTemplate', $this->data);
	}
	
	public function do_filter(\Admin\Request $request) {
		// Select application meta data
		$meta = new AppMetaModel($this->app['db']);
		$meta->get()->filter(
			$meta->filterExpr()->eq('app_id', $request['consumer_key'])
		)->exec();
		$this->data['meta'] = $meta;
		
		// Select involved applications info
		$appModel = new ApplicationModel($this->app['db']);
		$appModel->get($request['consumer_key'])->exec();
		$this->data['app'] = array_combine($appModel->consumer_key, $appModel->title);
		
		$this->data['key'] = $request['consumer_key'];

		return $this->app['template']->render('Codes\ListTemplate', $this->data);
	}

	public function do_edit(\Admin\Request $request) {
		$model = new AppMetaModel($this->app['db']);
		$model->get($request['id'])->exec();
		$this->data['model'] = $model[0];

		return $this->app['template']->render('Codes\FormTemplate', $this->data);
	}

	public function do_add(\Admin\Request $request) {
		$model = new AppMetaModel($this->app['db']);
		$model[0] = array(
			'app_id' => $request['consumer_key']
		);
		$this->data['model'] = $model[0];

		return $this->app['template']->render('Codes\FormTemplate', $this->data);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
		$model = new AppMetaModel($this->app['db']);
		
		$model->get($form['id'])->exec();
		$model[0] = $form;
		if ($form['id']) {
			$model->update()->exec();
		} else {
			unset($model[0]->id);
			$model->insert()->exec();
		}

		$url = $this->app->getUrl('codes_filter', array('consumer_key' => $form['app_id']));
		return $this->app->redirect($url);
	}
}