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
	public $sort = "ord";
	public $sortDir = -1;
	public $page = 1;

	const PAGE_SIZE = 5;

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
		$this->navBar();
		echo '<div style="width: 41em">&nbsp</div>';
		foreach ($this->realties as $realty) $this->realtyBlock($realty, false);
		echo '<div style="width: 41em">&nbsp</div>';
		echo '<div class="down">';
		$this->navBar();
		echo '</div> ';
		$this->end();
		return parent::show();
	}

	public function navBarTab($name, $href, $selected, $linkSelected = false) {
		if (!$selected) {
			?>
		<span class="hfold">
			<a class="hiddenlink" href="<?php echo $href; ?>"><?php echo $name; ?></a>
		</span>
		<?php
		} else if ($linkSelected) {
			?>
		<span class="hfold selected">
			<a class="hiddenlink" href="<?php echo $href; ?>"><?php echo $name; ?></a>
		</span>
		<?php
		} else {
			?>
		<span class="hfold selected"><?php echo $name; ?></span>
		<?php
		}

	}

	public function navBarSortTab($name, $sort, $defDir) {
		if ($sort == $this->sort) $dir = -$this->sortDir; else $dir = $defDir;
		$s = $dir > 0 ? '<img src="/static/img/icons/sort_up.png">' : '<img src="/static/img/icons/sort_down.png">';

		$curReq = $_REQUEST;
		$curReq['sort'] = $sort;
		$curReq['dir'] = $dir;
		$this->navBarTab($s . "&nbsp;" . $name, \Ctl\RealtyCtl::link('index', $curReq), ($this->sort == $sort), true);
	}

	public function navBarPager($npages, $num = 0) {
		$curReq = $_REQUEST;

		if ($num) $start = $this->page - floor(floatval($num) / 2);
		else $start = 1;
		if ($start < 1) $start = 1;

		$end = $num ? $start + $num - 1 : $npages;
		if ($end > $npages) {
			$end = $npages;
			$start = $npages - $num + 1 > 0 ? $npages - $num + 1: $start;
		}

		if ($this->page > 1) {
			$curReq['page'] = $this->page - 1;
			echo '<span><a style="text-decoration: none; background-color: #CC0000;color: white;padding: 0 0.2em" href="' . \Ctl\RealtyCtl::link('index', $curReq) . '">&lt;</a></span>';
		}
		for ($i = $start; $i <= $end; $i++) {
			$curReq['page'] = $i;
			$this->navBarTab($i, \Ctl\RealtyCtl::link('index', $curReq), ($this->page == $i));
		}

		if ($this->page < $npages) {
			$curReq['page'] = $this->page + 1;
			echo '<span class="hfold"><a style="text-decoration: none; background-color: #CC0000;color: white;padding: 0 0.2em" href="' . \Ctl\RealtyCtl::link('index', $curReq) . '">&gt;</a></span>';
		}
		$curReq['page'] = 'all';
		$this->navBarTab('все', \Ctl\RealtyCtl::link('index', $curReq), ($this->page == 'all'));
	}

	public function navBar() {
		$npages = ceil(floatval($this->allRealties->count()) / self::PAGE_SIZE);

		$curCur = \Session::obj()->currency;
		?>
	<table class="navbar" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<span class="hfold"><b>сортировка:</b></span>
				<?php $this->navBarSortTab('популярность', 'ord', -1); ?>
				<?php $this->navBarSortTab('цена', 'price', 1); ?>
			</td>
			<td><span class="hfold"><b>валюты:</b></span>
				<?php
				foreach ($this->currencies as $currency) {
					$this->navBarTab($currency->name, \Ctl\IndexCtl::link('setCurrency', array('value' => $currency->id)), ($curCur['id'] == $currency->id));
				}; ?>
			</td>
			<td><span class="hfold"><b>страницы:</b></span>
				<?php $this->navBarPager($npages, 5); ?>
			</td>
		</tr>
	</table>
	<?php
	}
}
