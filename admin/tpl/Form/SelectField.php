<?php

namespace Form;

use Admin\Extension\Template\Template;

/**
 * Field of form
 *
 * Input data:
 * $data = array(
 *   'name' => 'name_of_form_field',
 *   'values' => array( 'value' => 'text' ),
 *   'selected' => 'key_of_selected_item',
 *   ['empty' => true],
 * );
 */
class SelectField extends Template {

	protected function show($data, $content = null) {
		$name     = $data['name'];
		$values   = $data['values'];
		$selected = $data['selected'];
		?>
	<select name="form[<?php echo $name; ?>]">
	<?php if (isset($data['empty']) && true === $data['empty']): ?>
		<option value="" <?php echo ('' == $selected) ? 'selected' : ''; ?>>---</option>
	<?php endif; ?>
	<?php foreach ($values as $value => $text): ?>
		<option value="<?php echo $value; ?>" <?php echo $selected == $value ? 'selected' : ''; ?>>
			<?php echo $text; ?>
		</option>
	<?php endforeach; ?>
	</select>
	<?php

	}
}