<?php
/**
 * User: dualavatara
 * Date: 4/2/12
 * Time: 1:29 AM
 */
namespace Ctl;

class TemplateCtl extends BaseCtl {
	public function main() {
		$view = $this->disp->di()->TemplateView();

		$view->settings = $this->disp->di()->SettingModel();
		$view->settings->get()->all()->exec();

		//currecy list for header selector
		$view->currencies = $this->disp->di()->CurrencyModel();
		$view->currencies->get()->all()->order('id')->exec();

		//articles to the footer
		$view->articlesUsefull = $this->disp->di()->ArticleModel();
		$view->articlesUsefull->get()->filter($view->articlesUsefull->filterExpr()->eq('type', \ArticleModel::TYPE_USEFULL)
			->_and()->eq('flags', \ArticleModel::FLAG_VISIBLE)->_and()->eq('flags', \ArticleModel::FLAG_FOOTER))
			->order('ord', true)->exec();

		//header banners
		$view->bannersHead = $this->disp->di()->BannerModel();
		$view->bannersHead->get()->filter($view->bannersHead->filterExpr()->eq('type', \BannerModel::TYPE_240X100)->_and()
			->eq('flags', \BannerModel::FLAG_HEAD))->limit(4)->exec();

		return $view;
	}

	static public function link($method, $params) {
	}
}
