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
		$tpl = $this->disp->di()->TemplateCtl($this->disp)->main();
		$leftCol = $this->disp->di()->SearchColumnCtl($this->disp)->main();

		$mainView = $this->disp->di()->RealtyListView($this);

		if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'ord';
		if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 1;

		$mainView->sort = $_REQUEST['sort'];
		$mainView->sortDir = $_REQUEST['dir'];
		$mainView->page = $_REQUEST['page'];

		$realty = $this->disp->di()->RealtyModel();
		$mainView->realties = $realty->getList(array(), array($_REQUEST['sort'] => $_REQUEST['dir']));
		$mainView->realties->loadDependecies();

		$mainView->currencies = $this->disp->di()->CurrencyModel();
		$mainView->currencies->get()->all()->order('id')->exec();

		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($mainView->show());
		return $tpl;
	}

	static public function link($method, $params) {
		switch($method) {
			case 'profile' : return '/realty/profile/' . $params['id'];
			case 'index' : return '/realty'. '?' . http_build_query($params);
			default: throw new \NotFoundException();
		}
	}
}
