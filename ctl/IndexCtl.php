<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 7:48 PM
 */
class IndexCtl {

	/**
	 * @var IDispatcher
	 */
	private $disp;

	function __construct(IDispatcher $disp) {
		$this->disp = $disp;
	}


	public function main() {
		$settings = $this->disp->di()->SettingModel();
		$settings->get()->all()->exec();

		$currencies = $this->disp->di()->CurrencyModel();
		$currencies->get()->all()->exec();

		$view = $this->disp->di()->TemplateView('index.html');
		$output = $view->show(array(
			'settings' => $settings, 'currencies' => $currencies
		));
		return $output;
	}
}
