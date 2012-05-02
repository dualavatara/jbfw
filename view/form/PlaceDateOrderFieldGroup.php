<?php
/**
 * User: dualavatara
 * Date: 4/27/12
 * Time: 12:25 PM
 */

namespace View\Form;

class PlaceDateOrderFieldGroup extends Field {
	public $items;
	public $defaults;

	public function __construct($labels, $name, $items, $padding = false, $defaults = array()) {
		parent::__construct($labels, $name, $padding);
		$this->items = $items;
		$this->defaults = array('date' => '', 'hour' => 11, 'minute' => 0);
		foreach ($defaults as $k => $v) {
			$this->defaults[$k] = $v;
		}
		;
	}

	public function html() {
		$this->value['date'] = $this->value['date'] ? $this->value['date'] : $this->defaults['date'];
		$this->value['hour'] = $this->value['hour'] ? $this->value['hour'] : $this->defaults['hour'];
		$this->value['minute'] = $this->value['minute'] ? $this->value['minute'] : $this->defaults['minute'];
		ob_start();
		?>
	<div style="position: relative;margin-top: 0.5em;text-align: right;">
		<span style="position: absolute;top: -1.5em;left: 1em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[0]; ?>
			</i>
		</span>

		<select name="<?php echo $this->fieldName(); ?>[city]" id="<?php echo $this->fieldName(); ?>[city]"
				style="width: 16.2em;"
				class="resort"
				onchange="var n = '<?php echo addcslashes(addcslashes($this->fieldName() . '[place]', '[]'), '[]'); ?>';
					$.ajax({
					url: 'places/' + $(this).val(),
					context: $('#' + n),
					dataType: 'html',
					success: function(data) {
					$(this).empty();
					$(this).append(data);
					}
					})
					"
			>
			<?php
			foreach ($this->items as $k => $v) {
				$selected = ($k == $this->value['city']) ? 'selected' : '';
				?>
				<option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
				<?php
			};
			?>
		</select>
	</div>

	<div style="position: relative;margin-top: 1.5em;text-align: right;">
		<span style="position: absolute;top: -1.5em;left: 1em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[1]; ?>
			</i>
		</span>

		<select name="<?php echo $this->fieldName(); ?>[place]" id="<?php echo $this->fieldName(); ?>[place]"
				style="width: 16.2em;">

		</select>
	</div>
	<div style="position: relative;margin-top: 1em;padding-top: 1em; text-align: right;">
			<span style="position: absolute;top: 0;left: 1.2em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[3]; ?>
			</i>
		</span>
			<span style="position: absolute;top: 0;left: 11.2em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[4]; ?>
			</i>
		</span>
		<script>
			$(function () {
				var name = '<?php echo addcslashes(addcslashes($this->fieldName() . '[date]', '[]'), '\\'); ?>';
				$("#" + name).datepicker();
			});
		</script>
		<input type="text" class="textinput datepicker" id="<?php echo $this->fieldName(); ?>[date]"
			   name="<?php echo $this->fieldName(); ?>[date]"
			   value="<?php echo $this->value['date']; ?>" style="width: 5.5em;padding-left:0.5em;">
		<select name="<?php echo $this->fieldName(); ?>[hour]" id="<?php echo $this->fieldName(); ?>[hour]"
				style="width: 4.5em;">
			<?php
			for ($i = 0; $i < 24; $i++) {
				$selected = ($i == $this->value['hour']) ? 'selected' : '';
				?>
				<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php printf("%02d", $i); ?></option>
				<?php
			};
			?>
		</select>
		<select name="<?php echo $this->fieldName(); ?>[minute]" id="<?php echo $this->fieldName(); ?>[minute]"
				style="width: 4.5em;">
			<?php
			for ($i = 0; $i < 60; $i++) {
				$selected = ($i == $this->value['minute']) ? 'selected' : '';
				?>
				<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php printf("%02d", $i); ?></option>
				<?php
			};
			?>
		</select>
	</div>
	<?php
		return ob_get_clean();
	}
}
