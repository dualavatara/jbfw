<?php
/**
 * User: zhukov
 * Date: 28.02.12
 * Time: 3:50
 */

namespace ctl;

require_once 'model/CurrencyModel.php';
require_once 'admin/lib/StdController.php';

class Price extends \Admin\StdController {

	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'price_list', 'Price', $app);
		$cur = new \CurrencyModel($this->app['db']);
		$cur->get()->all()->exec();
		;
		foreach($cur as $row) {
			$this->data['currencies'][$row->id] = $row->name;
		}
	}
}
