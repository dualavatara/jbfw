<?php
/**
 * User: dualavatara
 * Date: 3/4/12
 * Time: 9:51 PM
 */

namespace Form;

use Admin\Extension\Template\Template;

class SearchSelectField extends Template {

	protected function show($data, $content = null) {
		$name = $data['name'];
		$value = $data['value'];
		$display_value = $data['display_value'];
		$label = $data['label'];
		$url = $data['rest_url']
		?>
	<input class="searchselect"
		   name="<?php echo $name; ?>"
		   id="<?php echo $name; ?>"
		   value="<?php echo $value; ?>"
		   display-value="<?php echo $display_value; ?>"
		   rest-url="<?php echo $url; ?>"
		   size="30"
		/>
	<?php

	}
}