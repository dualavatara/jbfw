<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 9:09 AM
 */

namespace Ctl;

class CarCtl extends BaseCtl {
	static public function link($method, $params) {
		switch($method) {
			case 'profile' : return '/car/profile/' . $params['id'];
			case 'index' : return '/car'. '?' . http_build_query($params);
			default: throw new \NotFoundException();
		}
	}

	public function index() {
		$tpl = $this->disp->di()->TemplateCtl($this->disp)->main();
		$leftCol = $this->disp->di()->SearchColumnCtl($this->disp)->main();

		$mainView = $this->disp->di()->CarListView($this);

		if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'ord';
		if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 1;

		$mainView->sort = $_REQUEST['sort'];
		$mainView->sortDir = $_REQUEST['dir'];
		$mainView->page = $_REQUEST['page'];

		$ptype = \PriceModel::TYPE_RENT;
		if ($_REQUEST['type'] == 'sell') $ptype = \PriceModel::TYPE_SELL;
		$realty = $this->disp->di()->CarModel();
		$mainView->cars = $realty->getList(array(), array($_REQUEST['sort'] => $_REQUEST['dir']), $ptype);
		$mainView->type = $_REQUEST['type'];

		$mainView->currencies = $this->disp->di()->CurrencyModel();
		$mainView->currencies->get()->all()->order('id')->exec();

		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($mainView->show());
		return $tpl;
	}

	public function profile($carId) {
		$tpl = $this->disp->di()->CarView($this->disp);
		return $tpl;
	}
}
