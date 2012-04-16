<?php

namespace Form;

use Admin\Extension\Template\Template;

/**
 * Extends of standard JqueryUI datepicker
 * 
 * @link http://trentrichardson.com/examples/timepicker/
 */
class DateTimeField extends Template {

	protected function show($data, $content = null) {
		$name  = $data['name'];
		$value = $data['value'];
		$config = $this->app->getConfig();
		?>
	<input id="<?php echo $name; ?>" readonly="readonly"/>
	<input type="hidden" name="form[<?php echo $name; ?>]" value="<?php echo $value; ?>"/>
	<script type="text/javascript">
		if (null == $.timepicker) {
			$('body').append('<script type="text/javascript" src="<?=$config->baseUrl?>/static/jquery/jquery-ui-timepicker-addon.min.js" />');
		}
		var date = new Date('<?php $d = new \DateTime($value); echo $d->format("F d, Y H:i:s"); ?>');
		//date = ('' == date) ? null : new Date(date);
		<?php echo '$(\'#'.$name.'\')'; ?>.datetimepicker({
			dateFormat:'dd.mm.yy',
			timeFormat: 'hh:mm:ss',
			altField:'input[name="form[<?php echo $name; ?>]"]',
			altFormat:'yy-mm-dd',
			stepMinute: 15,
			showButtonPanel: false,
			altFieldTimeOnly:false
		}).datetimepicker('setDate', (new Date())).datetimepicker(
				'setDate', date
		).keydown(function (e) {
					if (e.which == 8 || e.which == 46) { // backspace or delete button pressed
						$(this).datepicker('setDate', null);
						return false;
					}
				});
	</script>
	<?php
	}
}