<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 7:48 PM
 */

require_once 'ctl/Ctl.php';

class IndexCtl extends Ctl {

	public function main() {
		$settings = $this->disp->di()->SettingModel();
		$settings->get()->all()->exec();

		$currencies = $this->disp->di()->CurrencyModel();
		$currencies->get()->all()->order('id')->exec();

		$articlesUsefull = $this->disp->di()->ArticleModel();
		$articlesUsefull->get()->filter($articlesUsefull->filterExpr()->eq('type', ArticleModel::TYPE_USEFULL)->_and()
			->eq('flags', ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', ArticleModel::FLAG_FOOTER))
			->order('ord', true)->exec();

		$articles = $this->disp->di()->ArticleModel();
		$articles->get()->filter($articles->filterExpr()->eq('type', ArticleModel::TYPE_ARTICLE)->_and()
			->eq('flags', ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', ArticleModel::FLAG_TOINDEX))
			->order('ord', true)->limit(3)->exec();

		//header banners
		$bannersHead = $this->disp->di()->BannerModel();
		$bannersHead->get()->filter($bannersHead->filterExpr()->eq('type', BannerModel::TYPE_240X100)->_and()
			->eq('flags', BannerModel::FLAG_HEAD))->limit(4)->exec();

		//left column banners
		$bannersLeft = $this->disp->di()->BannerModel();
		$bannersLeft->get()->filter($bannersLeft->filterExpr()->eq('flags', BannerModel::FLAG_LEFTCOL))->exec();

		//realty selection for index
		$realties = $this->disp->di()->RealtyModel();
		$realties->get()->filter($realties->filterExpr()->eq('flags', ArticleModel::FLAG_VISIBLE))->exec();
		$realties->loadDependecies();

		$view = $this->disp->di()->TemplateView('index.html');
		$output = $view->show(array(
			'settings' => $settings,
			'currencies' => $currencies,
			'articlesUsefull' => $articlesUsefull,
			'articles' => $articles,
			'bannersHead' => $bannersHead,
			'bannersLeft' => $bannersLeft,
			'realties' => $realties,
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
		$c = $this->disp->di()->CurrencyModel();
		$c->get($request['value'])->exec();
		if ($c->count()) Session::obj()->currency = $c[0]->all();
		return $this->disp->redirect($this->disp->getReferer());
	}
}
