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
		<script>
			$(function () {
				$('#auto\\[place_from\\]\\[date\\]').datepicker({
					minDate: new Date(),
					onSelect: function(dateText, inst) {
						$('#auto\\[place_to\\]\\[date\\]').datepicker( "option", "minDate",  $(this).datepicker( "getDate" ));
					}
				});

				$('#auto\\[place_to\\]\\[date\\]').datepicker({
					minDate: new Date(),
					onSelect: function(dateText, inst) {
						$('#auto\\[place_from\\]\\[date\\]').datepicker( "option", "maxDate", $(this).datepicker( "getDate" ) );
					}
				});
			});
		</script>

		<?php

		$autoTab = new Tab('auto', 'авто', $this->autoSearchForm->html(), '0px');
		$rentTab = new Tab('rent', 'отдых', $this->rentSearchForm->html(), '6px');
		$sellTab = new Tab('sell', 'недвижимость', $this->sellSearchForm->html(), '2px');


		$tabView = new TabView('searchtabview');
		$tabView->addTab($autoTab);
		//$tabView->addTab($rentTab);
		//$tabView->addTab($sellTab);

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
