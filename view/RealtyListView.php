<?php
/**
 * User: dualavatara
 * Date: 4/3/12
 * Time: 3:17 AM
 */

namespace View;

class RealtyListView extends BaseView {
	private $allRealties;
	public $realties;
	public $currencies;

	function __construct($ctl) {
		$this->ctl = $ctl;
	}


	public function show() {
		if (!$this->page) $this->page = 1;
		$this->allRealties = $this->realties;
		if ($this->page != 'all') {
			$from = ($this->page - 1) * \Settings::obj()->get()->getPerPage();
			$to = $from + \Settings::obj()->get()->getPerPage() -1;
			$this->realties = $this->realties->slice($from, $to);
			$this->realties->loadDependecies();
		}
		$this->start();

		$this->columnHeader("Недвижимость в Черногории");

		?>
	<div class="path">
		<a class="grey" href="/">главная</a> /
		<span
			class="selected">недвижимость</span>
	</div>
	<?php
	if ($this->realties->count()) {
		$this->navBar(ceil(floatval($this->allRealties->count()) / \Settings::obj()->get()->getPerPage()));
		echo '<div style="width: 41em">&nbsp</div>';
		foreach ($this->realties as $realty) $this->realtyBlock($realty, true);
		echo '<div style="width: 41em">&nbsp</div>';
		echo '<div class="down">';
		$this->navBar(ceil(floatval($this->allRealties->count()) / \Settings::obj()->get()->getPerPage()));
		echo '</div> ';
	} else {
		?>
	<div style="padding: 2em;font-size: 1.5em;">К сожалению мы не нашли предложений по вашему запросу.
		Попробуйте изменить параметры поиска или свяжитесь с нами по телефону <?php echo \Settings::obj()->get()->getPhone1();?> или email
		<a href="mailo:<?php  echo \Settings::obj()->get()->getEmail();?>"><?php  echo \Settings::obj()->get()->getEmail();?></a>, мы обязательно подберем подходящий Вам вариант!</div>
	<?
	}
		$this->end();
		return parent::show();
	}


}
