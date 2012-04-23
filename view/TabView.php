<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 6:18 PM
 */
namespace View;

class TabView extends BaseView {
	public $tabs;
	public $name;
	public $selected;

	function __construct($name) {
		$this->name = $name;
	}


	public function addTab(Tab $tab) {
		$this->tabs[] = $tab;
	}

	public function show() {
		$this->start();
		?>
	<div id="<?php echo $this->name; ?>">
		<?php
		$this->foldersSwitchScript();
		$this->showFolderBar();
		?>
		<div id="tabcontent" style="z-index: 1000;clear:both; position: relative;top:-9px;">
			<?php
			$visible = 'block';
		foreach ($this->tabs as $tab) {
			?>
			<div style="display: <?php echo $visible; ?>;"  class="tabcontent" name="<?php echo $tab->name; ?>">
				<?php echo $tab->content; ?>
			</div>
			<?php
			$visible = 'none';
		}
			?>
		</div>
	</div>
	<?php
		$this->end();
		return parent::show();
	}

	public function foldersSwitchScript() {
		?>
	<script>
		$(function () {
			var viewname = '<?php echo $this->name;?>';
			$('#' + viewname + ' div[id^="tab_"]').click(function () {
				$('#' + viewname + ' div[id^="tab_"]').removeClass('selected');
				$(this).addClass('selected');
				$('.tabcontent').css('display', 'none');
				$('.tabcontent[name="' + $(this).attr('name') + '"]').css('display', 'block');
			});

			<?php if ($this->selected) { ?>
			var selected = '#tab_<?php echo $this->selected; ?>';
			$('#' + viewname + ' div[id^="tab_"]').removeClass('selected');
			$(selected).addClass('selected');
			$('.tabcontent').css('display', 'none');
			$('.tabcontent[name="' + $(selected).attr('name') + '"]').css('display', 'block');
			<?php }; ?>
		});
	</script>
	<?php
	}

	public function showFolderBar() {
		?>
	<div id="catfolds" style="z-index: 100;">
		<?php
		$selected = true;
		foreach ($this->tabs as $tab) {
			$this->showFolder($tab, $selected);
			$selected = false;
		}
		?>
	</div>
	<?php
	}

	public function showFolder($tab, $selected) {
		?>
	<div id="tab_<?php echo $tab->name; ?>" name="<?php echo $tab->name; ?>"
		  class="tabfold <?php echo $selected ? "selected" : ""; ?>"
		  style="margin-left: <?php echo $tab->margin; ?>;">
		<?php echo $tab->text; ?>
	</div>
	<?php
	}
}
