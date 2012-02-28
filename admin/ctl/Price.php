<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:50
 */

require_once("model/PriceModel.php");

class Price extends Admin\Controller{
	private $data = array(
		'menu' => 'modules',
		'section' => 'price_list'
	);

	public function do_list() {
		$model = new PriceModel($this->app['db']);
		$model->get()->all()->exec();

		$this->data['model'] = $model;

		return $this->app['template']->render('Price\ListTemplate', $this->data);
	}
}
