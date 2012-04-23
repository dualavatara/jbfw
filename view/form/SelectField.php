<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 8:32 PM
 */

namespace View\Form;

class SelectField extends Field {
	public $items;


	public function __construct($label, $name, $items, $padding = '') {
		$this->items = $items;
		parent::__construct($label, $name, $padding);
	}

	public function html() {
		ob_start();
		?>
	<label for="<?php echo $this->fieldName(); ?>"><?php echo $this->label; ?>:</label>
	<select name="<?php echo $this->fieldName(); ?>" id="<?php echo $this->fieldName(); ?>">
		<?php
		foreach ($this->items as $k => $v) {
			$selected = ($k == $this->value) ? 'selected' : '';
			?>
			<option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
			<?php
		};
		?>
	</select>
	<?php
		return ob_get_clean();
	}
}
