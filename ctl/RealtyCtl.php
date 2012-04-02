<?php
/**
 * User: dualavatara
 * Date: 4/2/12
 * Time: 12:38 AM
 */

namespace Ctl;

class RealtyCtl extends BaseCtl {
	public function profile($realtyId) {
		$tpl = $this->disp->di()->TemplateCtl($this->disp)->main();
		$leftCol = $this->disp->di()->SearchColumnCtl($this->disp)->main();

		$mainView = $this->disp->di()->RealtyView();

		$realty = $this->disp->di()->RealtyModel();
		$mainView->realty = $realty->getRealty($realtyId);
		$mainView->realty->loadDependecies();


		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($mainView->show());
		return $tpl;
	}

	public function index() {

	}

	static public function link($method, $params) {
		switch($method) {
			case 'profile' : return '/realty/profile/' . $params['id'];
			case 'index' : return '/realty';
			default: throw new \NotFoundException();
		}
	}
}
