<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 10:18 PM
 */
namespace View\Form;

class TextField extends Field{
	public $size;
	public function __construct($label, $name, $size = false, $padding = false) {
		$this->size = $size;
		parent::__construct($label, $name, $padding);
	}

	public function html() {
		ob_start();
		?>
	<label for="<?php echo $this->fieldName(); ?>"><?php echo $this->label; ?>:</label>

	<input type="text" class="textinput" id="<?php echo $this->fieldName(); ?>"
		   name="<?php echo $this->fieldName(); ?>"
		   value="<?php echo $this->value; ?>"
		<?php if ($this->size) echo 'size="' . $this->size . '"'; ?>>
	<?php
		return ob_get_clean();
	}
}
