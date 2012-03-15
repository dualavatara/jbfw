<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 5:15
 */

namespace Form;

use Admin\Extension\Template\Template;

class FlagsField extends Template {

	protected function show($data, $content = null) {
		$escName = addcslashes(quotemeta($data['name']), "\\");
		?>
		<script type="text/javascript">
			$(function () {
				var flag = <?php echo '$("#' . $escName . '")'; ?>.attr("value");
				$('input[id^="<?php echo $data['name'] . '_'; ?>"]').each(
					function (i, e) {
						if (parseInt(flag) & parseInt($(e).attr("value"))) $(e).attr('checked', true);
						else $(e).attr('checked', false);
						$(e).change(
							function(o) {
								var flags = <?php echo '$("#' . $escName . '")'; ?>;
								var obj = o.target;
								if ($(obj).attr("checked")) flags.attr("value", flags.attr("value") | $(obj).attr("value"));
								else flags.attr("value", flags.attr("value") & (~$(obj).attr("value")));
							}
						)
					}
				)
			});
		</script>
		<fieldset>
			<legend><?php echo $data['title']; ?></legend>
			<input type="hidden" id="<?php echo $data['name']; ?>" name="<?php echo $data['name']; ?>" value="<?php  if ($data['value']) echo intval($data['value']->get());?>" />
			<?php
			foreach($data['flags'] as $key => $value) {
			?>
				<input type="checkbox" id="<?php echo $data['name'] . '_' . $key; ?>" value="<?php echo $key; ?>"/>
				<label for="<?php echo $data['name'] . '_' . $key; ?>"><?php echo $value; ?></label><br />
			<?php };?>
		</fieldset>
		<?php
	}
}
