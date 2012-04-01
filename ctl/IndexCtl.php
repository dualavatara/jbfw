<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 7:48 PM
 */

require_once 'ctl/Ctl.php';

class IndexCtl extends Ctl {

	public function main() {
		$tpl = $this->disp->di()->TemplateView();
		$view = $this->disp->di()->IndexView($tpl);
		$tpl->settings = $this->disp->di()->SettingModel();
		$tpl->settings->get()->all()->exec();

		$tpl->currencies = $this->disp->di()->CurrencyModel();
		$tpl->currencies->get()->all()->order('id')->exec();

		$tpl->articlesUsefull = $this->disp->di()->ArticleModel();
		$tpl->articlesUsefull->get()->filter($tpl->articlesUsefull->filterExpr()->eq('type', ArticleModel::TYPE_USEFULL)->_and()
			->eq('flags', ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', ArticleModel::FLAG_FOOTER))
			->order('ord', true)->exec();

		$view->articles = $this->disp->di()->ArticleModel();
		$view->articles->get()->filter($view->articles->filterExpr()->eq('type', ArticleModel::TYPE_ARTICLE)->_and()
			->eq('flags', ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', ArticleModel::FLAG_TOINDEX))
			->order('ord', true)->limit(3)->exec();

		//header banners
		$tpl->bannersHead = $this->disp->di()->BannerModel();
		$tpl->bannersHead->get()->filter($tpl->bannersHead->filterExpr()->eq('type', BannerModel::TYPE_240X100)->_and()
			->eq('flags', BannerModel::FLAG_HEAD))->limit(4)->exec();

		//left column banners
		$view->bannersLeft = $this->disp->di()->BannerModel();
		$view->bannersLeft->get()->filter($view->bannersLeft->filterExpr()->eq('flags', BannerModel::FLAG_LEFTCOL))->exec();

		//realty selection for index
		$view->realties = $this->disp->di()->RealtyModel();
		$view->realties->get()->filter($view->realties->filterExpr()->eq('flags', ArticleModel::FLAG_VISIBLE))->exec();
		$view->realties->loadDependecies();


		$output = $view->show();
		return $output;
	}

	public function setLang() {
		$request = $this->disp->getRequest();
		Session::obj()->lang = $request['value'];
		return $this->disp->redirect($this->disp->getReferer());
	}

	public function setCurrency() {
		$request = $this->disp->getRequest();
		$c = $this->disp->di()->CurrencyModel();
		$c->get($request['value'])->exec();
		if ($c->count()) Session::obj()->currency = $c[0]->all();
		return $this->disp->redirect($this->disp->getReferer());
	}
}
