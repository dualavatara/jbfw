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
		$currencies->get()->all()->order('id')->exec();

		$articlesUsefull = $this->disp->di()->ArticleModel();
		$articlesUsefull->get()->filter(
			$articlesUsefull->filterExpr()->
				eq('type', ArticleModel::TYPE_USEFULL)->
				_and()->eq('flags', ArticleModel::FLAG_VISIBLE)->
				_and()->eq('flags', ArticleModel::FLAG_FOOTER)
		)->order('ord', true)->exec();
		$view = $this->disp->di()->TemplateView('index.html');
		$output = $view->show(array(
			'settings' => $settings, 'currencies' => $currencies, 'articlesUsefull' => $articlesUsefull
		));
		return $output;
	}

	public function setLang() {
		$request = $this->disp->getRequest();
		Session::obj()->lang = $request['value'];
		return $this->disp->redirect($this->disp->getReferer());
	}

	public function setCurrency() {
		$request = $this->disp->getRequest();
		Session::obj()->currency = $request['value'];
		return $this->disp->redirect($this->disp->getReferer());
	}
}
