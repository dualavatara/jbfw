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

		if ($_REQUEST['form']) { //search form incoming
			$form = $_REQUEST[$_REQUEST['form']];
			list($obj, $id) = explode('_', $form['type']);
			if ($obj == 'realty') {
				$mainView->realties->filterType($id);
				if ($form['resort']) $mainView->realties->filterByField('resort_id', function ($val) use ($form) { return $val == $form['resort_id'];});
				if ($form['from'] || $form['to']) $mainView->realties->filterPricesDate($form['from'], $form['to']);
				if ($form['rooms']) $mainView->realties->filterByField('rooms', function ($val) use ($form) { return $val == $form['rooms'];});
				if ($form['adults']) $mainView->realties->filterByField('adults', function ($val) use ($form) { return $val >= $form['adults'];});
				if ($form['kids']) $mainView->realties->filterByField('kids', function ($val) use ($form) { return $val >= $form['kids'];});

				if ($form['price_sell']) $mainView->realties->filterByPrice(\PriceModel::TYPE_SELL,
					function ($val) use ($form) { return $val >= $form['price_sell']['from'] && $val <= $form['price_sell']['to'];});

				if ($form['area']) $mainView->realties->filterByField('area',
					function ($val) use ($form) { return $val >= $form['area']['from'] && $val <= $form['area']['to'];});
				if ($form['plotarea']) $mainView->realties->filterByField('plotarea',
					function ($val) use ($form) {
						return $val >= $form['plotarea']['from'] && $val <= $form['plotarea']['to'];
					});

				if ($form['miscflags'][\RealtyModel::MISCFLAG_GARDEN]) $mainView->realties->filterByField('miscflags',
					function ($val) use ($form) {
						return $val & \RealtyModel::MISCFLAG_GARDEN;
					});
				if ($form['miscflags'][\RealtyModel::MISCFLAG_SAFEDOOR]) $mainView->realties->filterByField('miscflags',
					function ($val) use ($form) { return $val & \RealtyModel::MISCFLAG_SAFEDOOR;});
				if ($form['miscflags'][\RealtyModel::MISCFLAG_FIRSTLINE]) $mainView->realties->filterByField('miscflags',
					function ($val) use ($form) { return $val & \RealtyModel::MISCFLAG_FIRSTLINE;});

				if ($form['flags'][\RealtyModel::FLAG_DISCOUNT]) $mainView->realties->filterByField('flags',
					function ($val) use ($form) { return $val & \RealtyModel::FLAG_DISCOUNT;});
			}
			if ($obj == 'app') {
				$mainView->realties->app->filterType($id);
				if ($form['from'] || $form['to']) $mainView->realties->app->filterPricesDate($form['from'], $form['to']);
				if ($form['rooms']) $mainView->realties->app->filterByField('rooms', function ($val) use ($form) { return $val == $form['rooms'];});
				if ($form['adults']) $mainView->realties->app->filterByField('adults', function ($val) use ($form) { return $val == $form['adults'];});
				if ($form['kids']) $mainView->realties->app->filterByField('kids', function ($val) use ($form) { return $val == $form['kids'];});

				if ($form['price_sell']) $mainView->realties->app->filterByPrice(\PriceModel::TYPE_SELL,
					function ($val) use ($form) { return $val >= $form['price_sell']['from'] && $val <= $form['price_sell']['to'];});

				if ($form['flags'][\RealtyModel::FLAG_DISCOUNT]) $mainView->realties->app->filterByField('flags',
					function ($val) use ($form) { return $val & \RealtyModel::FLAG_DISCOUNT;});
				$mainView->realties->filterHasApp();
			}


		}

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
