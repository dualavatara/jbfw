<?php

require_once 'model/account.model.php';
require_once 'model/application.model.php';

class Account extends Admin\Controller {
	
	private $data = array(
		'menu' => 'modules',
		'section' => 'account_list'
	);

	public function do_add() {
		$new = new AccountModel($this->app['db']);
		$new[0] = array();
		$this->data['model'] = $new[0];
		
		$all = new ApplicationModel($this->app['db']);
		$all->get()->all()->exec();

		$this->data['applications'] = $all;
		$this->data['linked'] = array();
		
		return $this->app['template']->render('Account\FormTemplate', $this->data);
	}
	
	public function do_delete(\Admin\Request $request) {
		$id = $request['id'];
		
		$model = new AccountModel($this->app['db']);
		$model->remove($id);

		$url = $this->app->getUrl('account_list');
		return $this->app->redirect($url);
	}
	
	public function do_edit(\Admin\Request $request) {
		$id = $request['id'];
		
		$account = new AccountModel($this->app['db']);
		$account->get($id)->exec();
		if (0 == $account->count()) {
			return $this->app->error404();
		}
		$this->data['model'] = $account[0];
		
		// Get already linked applications
		$linked = $account->getApplications($account[0]->id);
		
		// Get data of linked applications
		$app = new ApplicationModel($this->app['db']);
		$app->get($linked->app_key)->exec();
		$this->data['linked'] = $app;
		
		// Get data of other applications
		$other = new ApplicationModel($this->app['db']);
		$other->get()->filter(
			$other->filterExpr()->
					notEq('consumer_key', $linked->app_key)
		)->exec();

		$this->data['applications'] = $other;

		$deviceModel = new DevicesModel($this->app['db']);
		$devices = $deviceModel->getDeviceByAccount($account[0]->id);
		$this->data['devices'] = ($devices->count()) ? $devices->uid : array();
		
		return $this->app['template']->render('Account\FormTemplate', $this->data);
	}
	
	public function do_list() {
		$model = new AccountModel($this->app['db']);
		$model->get()->all()->exec();

		$this->data['model'] = $model;

        return $this->app['template']->render('Account\ListTemplate', $this->data);
	}
	
	public function do_save(\Admin\Request $request) {
		$form = $request['form'];

		unset($form['device']);
		
		$model = new AccountModel($this->app['db']);
		$model[0] = $form;
		if ($form['id']) {
			$model->update()->exec();
		} else {
			unset($model[0]->id);
			$model->insert()->exec();
		}
		
		// Edit linked applications
		$linked = $model->getApplications($model[0]->id);
		$linked_old = $linked->app_key;
		$linked_new = $form['linked'] ?: array();
		
		$add = array_diff($linked_new, $linked_old);
		foreach ($add as $consumer_key) {
			$model->addApplication($model[0]->id, $consumer_key);
		}
		
		$remove = array_diff($linked_old, $linked_new);
		foreach ($remove as $consumer_key) {
			$model->removeApplication($model[0]->id, $consumer_key);
		}
		
		$url = $this->app->getUrl('account_list');
		return $this->app->redirect($url);
	}
}