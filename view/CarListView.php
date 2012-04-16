<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 10:08 AM
 */
namespace View;

class CarListView extends BaseView {
	private $allCars;
	public $cars;
	public $currencies;
	public $type;

	function __construct($ctl) {
		$this->ctl = $ctl;
	}

	public function show() {
		if (!$this->page) $this->page = 1;
		$this->allCars = $this->cars;
		if ($this->page != 'all') {
			$from = ($this->page - 1) * (\Settings::obj()->get()->getPerPage() * 2);
			$to = $from + (\Settings::obj()->get()->getPerPage() * 2) -1;
			$this->cars = $this->cars->slice($from, $to);
		}
		$this->start();

		if ($this->type == 'rent') $this->columnHeader("Аренда автомобилей");
		else $this->columnHeader("Продажа автомобилей");

		?>
	<div class="path">
		<a class="grey" href="/">главная</a> /
		<span
			class="selected">автомобили</span>
	</div>
	<?php
		$this->navBar(ceil(floatval($this->allCars->count()) / (\Settings::obj()->get()->getPerPage() * 2)));
		echo '<div style="width: 41em">&nbsp</div>';
		$i = 0;
		foreach ($this->cars as $car) {
			$i++;
			$this->carsBlock($car, $i % 2);
		}
		echo '<div style="width: 41em">&nbsp</div>';
		echo '<div class="down">';
		$this->navBar(ceil(floatval($this->allCars->count()) / (\Settings::obj()->get()->getPerPage() * 2)));
		echo '</div> ';
		$this->end();
		return parent::show();
	}
}
