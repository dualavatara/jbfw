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
			$from = ($this->page - 1) * self::PAGE_SIZE;
			$to = $from + self::PAGE_SIZE -1;
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
		$this->navBar(ceil(floatval($this->allRealties->count()) / self::PAGE_SIZE));
		echo '<div style="width: 41em">&nbsp</div>';
		foreach ($this->realties as $realty) $this->realtyBlock($realty, false);
		echo '<div style="width: 41em">&nbsp</div>';
		echo '<div class="down">';
		$this->navBar(ceil(floatval($this->allRealties->count()) / self::PAGE_SIZE));
		echo '</div> ';
		$this->end();
		return parent::show();
	}


}
