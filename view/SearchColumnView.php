<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 8:27 AM
 */

namespace View;

class SearchColumnView extends BaseView{
	public $rentSearchForm;
	public $sellSearchForm;
	public $autoSearchForm;

	public function show() {
		$this->start();
		$this->columnHeader('Искать на сайте:', 'left');
		?>
	<div id="searchpad" style="position: relative">
		<?php

		$rentTab = new Tab('rent', 'аренда', $this->rentSearchForm->html(), '0px');
		$sellTab = new Tab('sell', 'продажа', $this->sellSearchForm->html(), '9px');
		$autoTab = new Tab('auto', 'авто', $this->autoSearchForm->html(), '7px');

		$tabView = new TabView('searchtabview');
		$tabView->addTab($rentTab);
		$tabView->addTab($sellTab);
		$tabView->addTab($autoTab);
		$tabView->selected = $_REQUEST['form'];
		echo $tabView->show();
		?>
	</div>
	<?php
		/** @noinspection PhpUndefinedVariableInspection */foreach ($this->bannersLeft as $banner) {
			$size = $this->bannersLeft->getSize($banner->type);
			?>
		<div>
			<a href="<?php echo $banner->link; ?>">
				<img src="<?php echo \Ctl\StaticCtl::link('get', array('key'=>$banner->image)); ?>" width="<?php echo $size->width; ?>"
					 height="<?php echo $size->height; ?>">
			</a>
		</div>
		<?php
		};
		$this->end();
		return $this->content;
	}

}
