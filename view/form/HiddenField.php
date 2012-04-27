<?php
/**
 * User: dualavatara
 * Date: 4/27/12
 * Time: 12:19 PM
 */
namespace View\Form;

class HiddenField extends Field{
	public $hiddenValue;
	public function __construct($name, $hiddenValue = '') {
		$this->hiddenValue = $hiddenValue;
		parent::__construct('', $name, '');
	}

	public function html() {
		$val = $this->hiddenValue ? $this->hiddenValue : $this->value;
		ob_start();
		?>

	<input type="hidden" id="<?php echo $this->fieldName(); ?>"
		   name="<?php echo $this->fieldName(); ?>"
		   value="<?php echo $val; ?>"
		<?php if ($this->size) echo 'size="' . $this->size . '"'; ?>>
	<?php
		return ob_get_clean();
	}
}
