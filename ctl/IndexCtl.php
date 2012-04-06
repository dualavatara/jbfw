<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 7:48 PM
 */

namespace Ctl;

class IndexCtl extends BaseCtl {

	public function main() {
		$tpl = $this->disp->di()->TemplateCtl($this->disp)->main();
		$leftCol = $this->disp->di()->SearchColumnCtl($this->disp)->main();
		$view = $this->disp->di()->IndexView($tpl);

		//articles for index
		$view->articles = $this->disp->di()->ArticleModel();
		$view->articles->get()->filter($view->articles->filterExpr()->eq('type', \ArticleModel::TYPE_ARTICLE)->_and()
			->eq('flags', \ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', \ArticleModel::FLAG_TOINDEX))
			->order('ord', true)->limit(3)->exec();

		//realty selection for index
		$view->realties = $this->disp->di()->RealtyModel();
		$view->realties->getList(array(\RealtyModel::FLAG_BEST));
		$view->realties->loadDependecies();

		$view->cars = $this->disp->di()->CarModel();
		$view->cars->getList(array(\CarModel::FLAG_BEST));
		//$view->cars->loadDependecies();

		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($view->show());
		return $tpl;
	}

	public function setLang() {
		$request = $this->disp->getRequest();
		\Session::obj()->lang = $request['value'];
		return $this->disp->redirect($this->disp->getReferer());
	}

	public function setCurrency() {
		$request = $this->disp->getRequest();
		$c = $this->disp->di()->CurrencyModel();
		$c->get($request['value'])->exec();
		if ($c->count()) \Session::obj()->currency = $c[0]->all();
		return $this->disp->redirect($this->disp->getReferer());
	}

	static public function link($method, $params) {
		switch($method) {
			case 'main' : return '/';
			case 'setLang' : return '/lang' . '?' . http_build_query($params);
			case 'setCurrency' : return '/currency' . '?' . http_build_query($params);
			default: throw new \NotFoundException();
		}
	}
}
