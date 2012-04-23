<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 9:44 PM
 */
namespace View\Form;

class DateField extends Field {
	public function __construct($label, $name, $padding = false) {
		parent::__construct($label, $name, $padding);
	}

	public function html() {
		ob_start();
		?>
	<label for="<?php echo $this->fieldName(); ?>"><?php echo $this->label; ?>:</label>
	<script>
		$(function() {
			var name = '<?php echo addcslashes(addcslashes($this->fieldName(), '[]'), '\\'); ?>';
			$( "#" + name ).datepicker();
		});
	</script>
	<input type="text" id="<?php echo $this->fieldName(); ?>" name="<?php echo $this->fieldName(); ?>" value="<?php echo $this->value; ?>">
	<?php
		return ob_get_clean();
	}
}
