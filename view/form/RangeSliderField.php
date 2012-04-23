<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 10:43 PM
 */
namespace View\Form;

class RangeSliderField extends Field {
	public $min;
	public $max;
	public $prefix;

	public function __construct($label, $name, $min, $max, $prefix = '', $padding = false) {
		parent::__construct($label, $name, $padding);
		$this->min = $min;
		$this->max = $max;
		$this->prefix = $prefix;
	}

	public function html() {
		if (is_array($this->value)) {
			if (!isset($this->value['from'])) $this->value['from'] = $this->min;
			if (!isset($this->value['to'])) $this->value['to'] = $this->max;
		} else $this->value = array('from' => $this->min, 'to' => $this->max);
		ob_start();
		?>
	<script>
		$(function () {
			var prefix = '<?php echo $this->prefix; ?>';
			var id = '#slider-range-' + '<?php echo addcslashes(addcslashes($this->fieldName(), '[]'), '\\'); ?>';
			var fromh = '#' + '<?php echo addcslashes(addcslashes($this->fieldName('', 'from'), '[]'), '\\'); ?>';
			var toh = '#' + '<?php echo addcslashes(addcslashes($this->fieldName('', 'to'), '[]'), '\\'); ?>';
			var amount = '#amount-' + '<?php echo addcslashes(addcslashes($this->fieldName(), '[]'), '\\'); ?>';
			$(id).slider({
				range:true,
				min: <?php echo $this->min; ?>,
				max: <?php echo $this->max; ?>,
				values:[ <?php echo $this->value['from']; ?>, <?php echo $this->value['to']; ?> ],
				slide:function (event, ui) {
					$(amount).html(prefix + '<span style="color: red" >' + ui.values[ 0 ] + '</span>' + " - " + prefix + '<span style="color: red" >' + ui.values[ 1 ] + '</span>');
					$(fromh).val(ui.values[ 0 ]);
					$(toh).val(ui.values[ 1 ]);
				}
			});
			$(amount).html(prefix + '<span style="color: red" >' + $(id).slider("values", 0) + '</span>' +
				" - " + prefix + '<span style="color: red" >' + $(id).slider("values", 1) + '</span>');
			$(fromh).val($(id).slider("values", 0));
			$(toh).val($(id).slider("values", 1));
		});
	</script>
	<div style="padding-bottom: 0.3em;"><span><?php echo $this->label; ?>&nbsp;&nbsp;&nbsp; </span><span id="amount-<?php echo $this->fieldName(); ?>"></span>
	</div>
	<input type="hidden" name="<?php echo $this->fieldName('','from'); ?>" id="<?php echo $this->fieldName('', 'from'); ?>">
	<input type="hidden" name="<?php echo $this->fieldName('', 'to'); ?>" id="<?php echo $this->fieldName('', 'to'); ?>">

	<div id="slider-range-<?php echo $this->fieldName(); ?>"></div>

	<?php
		return ob_get_clean();
	}
}
