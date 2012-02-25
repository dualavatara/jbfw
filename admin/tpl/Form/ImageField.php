<?php

namespace Form;

use Admin\Extension\Template\Template;

/**
 * Field of form for editing images.
 * Support images stored in data storage.
 * Show key of current image and show lightbox preview on click.
 * 
 * Input data:
 * $data = array(
 *   'name' => 'name_of_form_field',
 *   'key' => 'image_key_in_storage',
 * );
 */
class ImageField extends Template {
	
	function show($data, $content = null) {
			$name = $data['name'];
			$key = $data['key'];
		?>
		<input type="hidden" name="form[<?php echo $name; ?>]" value="<?php echo $key; ?>"/>
		<input type="file" name="<?php echo $name; ?>"/>
		<?php if ($key): ?>
			<? $this->showLink($key, 'static', array('key' => $key),'class="lightbox"')?>
		<?php endif; ?>
	<?
	}
}