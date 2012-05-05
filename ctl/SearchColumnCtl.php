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

		$view->rentSearchForm = new \View\Form\SearchForm('rent', '/realty');
		$realty = $this->disp->di()->RealtyModel();
		$app = $this->disp->di()->AppartmentModel();
		$types = array();
		$typesRealty = $realty->getTypes(\RealtyTypeModel::FLAG_SEARCH_RENT);
		foreach($typesRealty as $k => $v) $types['realty_' . $k] = $v;

		$typesApp = $app->getTypes(\AppartmentTypeModel::FLAG_SEARCH_RENT);
		foreach($typesApp as $k => $v) $types['app_' . $k] = $v;

		$view->rentSearchForm->add(new \View\Form\SelectField('Тип', 'type', $types, '0.5em 0 0.5em 2.5em'), true);

		$resort = $this->disp->di()->ResortModel();
		$resort->get()->all()->exec();
		$view->rentSearchForm->add(new \View\Form\SelectField('Курорт', 'resort', $resort->getArray('id', 'name'), '0.5em 0 1.5em 1em'), true);
		$view->rentSearchForm->add(new \View\Form\DateField('с', 'from', '0.5em 0 0.5em 0.8em'), true);
		$view->rentSearchForm->add(new \View\Form\DateField('по', 'to', '0.5em 0 1.5em 0em'), true);
		$view->rentSearchForm->add(new \View\Form\TextField('Комнат', 'rooms', 4, '0.5em 0 0.5em 4.5em'), true);
		$view->rentSearchForm->add(new \View\Form\TextField('Взрослых', 'adults', 4, '0.5em 0 0.5em 3.4em'), true);
		$view->rentSearchForm->add(new \View\Form\TextField('Детей', 'kids',4, '0.5em 0 0.5em 5em'), true);
		$view->rentSearchForm->add(new \View\Form\SubmitImgButton('/static/img/buttons/search_r.png', '0.5em 0 0.5em 6.3em'), true);

		$view->rentSearchForm->add(new \View\Form\FlagField('Сейфовая дверь', 'miscflags', \RealtyModel::MISCFLAG_SAFEDOOR, '0.5em 0em 0em 0em'));
		$view->rentSearchForm->add(new \View\Form\FlagField('Сад', 'miscflags', \RealtyModel::MISCFLAG_GARDEN, '0.5em 0em 0em 0em'));
		$view->rentSearchForm->add(new \View\Form\FlagField('Первая линия', 'miscflags', \RealtyModel::MISCFLAG_FIRSTLINE, '0.5em 0em 0em 0em'));
		$view->rentSearchForm->add(new \View\Form\FlagField('Скидка', 'flags', \RealtyModel::FLAG_DISCOUNT, '0.5em 0em 0em 0em'));



		$view->sellSearchForm = new \View\Form\SearchForm('sell', '/realty');
		$types = array();
		$typesRealty = $realty->getTypes(\RealtyTypeModel::FLAG_SEARCH_SELL);
		foreach($typesRealty as $k => $v) $types['realty_' . $k] = $v;

		$typesApp = $app->getTypes(\AppartmentTypeModel::FLAG_SEARCH_SELL);
		foreach($typesApp as $k => $v) $types['app_' . $k] = $v;

		$view->sellSearchForm->add(new \View\Form\SelectField('Тип', 'type', $types, '0.5em 0 0.5em 2.5em'), true);
		$view->sellSearchForm->add(new \View\Form\SelectField('Курорт', 'resort', $resort->getArray('id', 'name'), '0.5em 0 1.5em 1em'), true);
		$view->sellSearchForm->add(new \View\Form\RangeSliderField('Цена', 'price_sell', 1, 1000000, \Session::obj()->currency['sign'] . ' ', '0.5em 0.5em 0.5em 0.5em'), true);
		$view->sellSearchForm->add(new \View\Form\RangeSliderField('Площадь, м&#178;', 'area', 20, 1000,  ' ', '0.5em 0.5em 0.5em 0.5em'), true);
		$view->sellSearchForm->add(new \View\Form\RangeSliderField('Площадь участка, м&#178;', 'plotarea', 20, 10000,  ' ', '0.5em 0.5em 0.5em 0.5em'), false);
		$view->sellSearchForm->add(new \View\Form\SubmitImgButton('/static/img/buttons/search_r.png', '0.5em 0 0.5em 6.3em'), true);

		$view->sellSearchForm->add(new \View\Form\FlagField('Сейфовая дверь', 'miscflags', \RealtyModel::MISCFLAG_SAFEDOOR, '0.5em 0em 0em 0em'));
		$view->sellSearchForm->add(new \View\Form\FlagField('Сад', 'miscflags', \RealtyModel::MISCFLAG_GARDEN, '0.5em 0em 0em 0em'));
		$view->sellSearchForm->add(new \View\Form\FlagField('Первая линия', 'miscflags', \RealtyModel::MISCFLAG_FIRSTLINE, '0.5em 0em 0em 0em'));
		$view->sellSearchForm->add(new \View\Form\FlagField('Скидка', 'flags', \RealtyModel::FLAG_DISCOUNT, '0.5em 0em 0em 0em'));


		$view->autoSearchForm = new \View\Form\SearchForm('auto', '/car');
		$car = $this->disp->di()->CarModel();

		$resort = $this->disp->di()->ResortModel();
		$resort->get()->filter($resort->filterExpr()->eq('flags', \ResortModel::TYPE_AUTOSEARCH))->order('name')->exec();

		//$view->autoSearchForm->add(new \View\Form\SelectField('Предложение', 'price_type', array('rent'=> 'Аренда', 'sell' => 'Продажа'), '0.5em 0 0.5em 2.5em'), true);
		$view->autoSearchForm->add(new \View\Form\HiddenField('price_type', 'rent'), true);
		$view->autoSearchForm->add(new \View\Form\SelectField('Класс авто', 'type', $car->getTypes(), '0.5em 0 0.5em 0em'), true);
		$view->autoSearchForm->add(new \View\Form\Separator(), true);
		$view->autoSearchForm->add(new \View\Form\PlaceDateFieldGroup(array(
			'Получение',
			'Город получения авто',
			'Дата',
			'Время получения',
		), 'place_from', $resort->getArray('id', 'name'), '', array('date' => date_format(date_create('tomorrow'), "d.m.Y"))), true);
		$view->autoSearchForm->add(new \View\Form\Separator(), true);
		$view->autoSearchForm->add(new \View\Form\PlaceDateFieldGroup(array(
			'Возврат',
			'Город возврата авто',
			'Дата',
			'Время возврата',
		), 'place_to', $resort->getArray('id', 'name'), '', array('date' => date_format(date_create('+2 day'), "d.m.Y"))), true);
		//extended fields

		$view->autoSearchForm->add(new \View\Form\FlagField('Автоматическая трансмиссия', 'flags', \CarModel::FLAG_AUTOMAT, '0.5em 0em 0em 0em'));
		$view->autoSearchForm->add(new \View\Form\FlagField('Камера заднего вида', 'flags', \CarModel::FLAG_CAMERA, '0.5em 0em 0em 0em'));
		$view->autoSearchForm->add(new \View\Form\FlagField('Скидки', 'flags', \CarModel::FLAG_DESCOUNT, '0.5em 0em 0.5em 0em'));

		$view->autoSearchForm->add(new \View\Form\SubmitImgButton('/static/img/buttons/search_r.png', '0.5em 0 0.5em 6.3em'), true);

		return $view;
	}

	static public function link($method, $params) {
	}
}
