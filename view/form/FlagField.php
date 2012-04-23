<?php
/**
 * User: dualavatara
 * Date: 4/23/12
 * Time: 12:18 AM
 */
namespace View\Form;

class FlagField extends Field{
	public $flagvalue;
	public function __construct($label, $name, $flagvalue, $padding = false) {
		parent::__construct($label, $name, $padding);
		$this->flagvalue = $flagvalue;
	}

	public function html() {
		if (isset($this->value[$this->flagvalue])) $c = 'checked';
		else $c = '';
		ob_start();
		?>


	<input type="checkbox"
		   id="<?php echo $this->fieldName().'['.$this->flagvalue.']'; ?>"
		   name="<?php echo $this->fieldName().'['.$this->flagvalue.']'; ?>"
		   value="1"
		   <?php echo $c; ?>
		><label for="<?php echo $this->fieldName().'['.$this->flagvalue.']'; ?>"><?php echo $this->label; ?></label>
	<?php
		return ob_get_clean();
	}
}
