<?php

namespace Form;

use Admin\Extension\Template\Template;
		
class DateField extends Template {

	protected function show($data, $content = null) {
		$name = $data['name'];
		$value = $data['value'];
		?>
	<input id="<?php echo $name; ?>" readonly="readonly" />
	<input type="hidden" name="form[<?php echo $name; ?>]" value="<?php echo $value; ?>" />
	<script type="text/javascript">
		$('#<?php echo $name; ?>').datepicker({
			dateFormat: 'dd.mm.yy',
			altField: 'input[name="form[<?php echo $name; ?>]"]',
			altFormat: 'yy-mm-dd'
		}).datepicker(
			'setDate',
			new Date('<?php echo $value; ?>')
		).keydown(function(e){
			if (e.which == 8 || e.which == 46) { // backspace or delete button pressed
				$(this).datepicker('setDate', null);
				return false;
			}
		});
	</script>
	<?php
	}
}