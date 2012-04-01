<?php
/**
 * User: dualavatara
 * Date: 4/2/12
 * Time: 1:38 AM
 */

namespace Ctl;

class SearchColumnCtl extends BaseCtl {

	public function main() {
		$view = $this->disp->di()->SearchColumnView();

		$view->bannersLeft = $this->disp->di()->BannerModel();
		$view->bannersLeft->get()->filter($view->bannersLeft->filterExpr()->eq('flags', \BannerModel::FLAG_LEFTCOL))
			->exec();

		return $view;
	}

	static public function link($method, $params) {
	}
}
