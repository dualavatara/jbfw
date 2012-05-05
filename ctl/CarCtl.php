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
		if ($_REQUEST['form']) { //search form incoming
			$form = $_REQUEST[$_REQUEST['form']];
			if ($form['price_type'] == 'sell') $ptype = \PriceModel::TYPE_SELL;
		}
		$car = $this->disp->di()->CarModel();
		$mainView->cars = $car->getList(array(), array($_REQUEST['sort'] => $_REQUEST['dir']), $ptype);


		if ($_REQUEST['form']) { //search form incoming
			$form = $_REQUEST[$_REQUEST['form']];

			\Session::obj()->form = $form;

			$mainView->type = $form['price_type'];
			if ($form['type']) $mainView->cars->filterByField('type_id', function ($val) use ($form) { return $val == $form['type'];});
			if ($form['fuel']) $mainView->cars->filterByField('fuel', function ($val) use ($form) { return $val >= $form['fuel']['from'] && $val <= $form['fuel']['to'];});
			if ($form['seats']) $mainView->cars->filterByField('seats', function ($val) use ($form) { return $val >= $form['seats']['from'] && $val <= $form['seats']['to'];});
			if ($form['volume']) $mainView->cars->filterByField('volume', function ($val) use ($form) { return $val >= $form['volume']['from'] && $val <= $form['volume']['to'];});
			if ($form['baggage']) $mainView->cars->filterByField('baggage', function ($val) use ($form) { return $val >= $form['baggage']['from'] && $val <= $form['baggage']['to'];});
			if ($form['doors']) $mainView->cars->filterByField('doors', function ($val) use ($form) { return $val >= $form['doors']['from'] && $val <= $form['doors']['to'];});
			if ($form['min_age']) $mainView->cars->filterByField('min_age', function ($val) use ($form) { return $val >= $form['min_age']['from'] && $val <= $form['min_age']['to'];});

			if ($form['flags'][\CarModel::FLAG_SPUTNIK]) $mainView->cars->filterByField('flags',
				function ($val) use ($form) { return $val & \CarModel::FLAG_SPUTNIK;});
			if ($form['flags'][\CarModel::FLAG_CONDITIONER]) $mainView->cars->filterByField('flags',
				function ($val) use ($form) { return $val & \CarModel::FLAG_CONDITIONER;});
			if ($form['flags'][\CarModel::FLAG_DIESEL]) $mainView->cars->filterByField('flags',
				function ($val) use ($form) { return $val & \CarModel::FLAG_DIESEL;});
			if ($form['flags'][\CarModel::FLAG_AUTOMAT]) $mainView->cars->filterByField('flags',
				function ($val) use ($form) { return $val & \CarModel::FLAG_AUTOMAT;});
			if ($form['flags'][\CarModel::FLAG_DESCOUNT]) $mainView->cars->filterByField('flags',
				function ($val) use ($form) { return $val & \CarModel::FLAG_DESCOUNT;});
		}

		$mainView->currencies = $this->disp->di()->CurrencyModel();
		$mainView->currencies->get()->all()->order('id')->exec();

		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($mainView->show());
		return $tpl;
	}

	public function profile($carId) {
		$view = $this->disp->di()->CarView($this->disp);

		$car = $this->disp->di()->CarModel();
		$view->car = $car->getCar($carId);
		return $view;
	}

	public function order($carId) {
		$view = $this->disp->di()->CarOrderView($this->disp);

		$car = $this->disp->di()->CarModel();
		$view->car = $car->getCar($carId);
		$cro = $this->disp->di()->CarRentOfficeModel();
		$cro->get($view->car->office_id)->exec();
		if ($cro->count()) $view->carrentoffice = $cro[0];

		$resort = $this->disp->di()->ResortModel();
		$resort->get()->filter($resort->filterExpr()->eq('flags', \ResortModel::TYPE_AUTOSEARCH))->order('name')->exec();
		$view->resorts = $resort;
		return $view;
	}

	public function order2($carId) {
		$form = $_REQUEST['order'];

		\Session::obj()->order = $form;

		$view = $this->disp->di()->CarOrderView($this->disp);
		$view->step = 'step2';

		$car = $this->disp->di()->CarModel();
		$view->car = $car->getCar($carId);
		$cro = $this->disp->di()->CarRentOfficeModel();
		$cro->get($view->car->office_id)->exec();
		if ($cro->count()) $view->carrentoffice = $cro[0];

		return $view;
	}

	public function finish($carId) {
		$view = $this->disp->di()->CarOrderView($this->disp);
		$car = $this->disp->di()->CarModel();
		$view->car = $car->getCar($carId);
		$view->resorts = $this->disp->di()->ResortModel();
		$view->places = $this->disp->di()->PlaceModel();
		$body = $view->email();


		$message = \Swift_Message::newInstance()

		// Give the message a subject
			->setSubject('Order')

		// Set the From address with an associative array
			->setFrom(array('info@jb-travel.com' => 'JB-Travel site'))

		// Set the To addresses with an associative array
			->setTo(array('info@jb-travel.com'))

		// Give it a body
			->setBody($body, 'text/html')

		;
		$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465, 'ssl')
			->setUsername('kindcasper@jb-travel.com')
			->setPassword('Lj,hsqRfcgth')
		;
		$mailer = \Swift_Mailer::newInstance($transport);

		// Send the message
		$result = $mailer->send($message);
		/*$params = array(
			'from' => array('email' => 'info@jb-travel.com', 'user' => 'Site')
		);
		sendMail('testsubj', 'test body', 'dualavatara@gmail.com', $params);*/


		$view->step = 'finish';
		return $view;
	}


	public function places($resortId) {
		$view = $this->disp->di()->PlacesView($this->disp);
		$view->step = 'places';

		$place = $this->disp->di()->PlaceModel();
		$place->get()->filter($place->filterExpr()->eq('resort_id', $resortId))->exec();
		$view->places = $place;
		return $view;
	}
}
