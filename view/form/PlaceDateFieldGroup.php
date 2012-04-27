<?php
/**
 * User: dualavatara
 * Date: 4/27/12
 * Time: 12:25 PM
 */

namespace View\Form;

class PlaceDateFieldGroup extends Field {
	public $items;

	public function __construct($labels, $name, $items, $padding = false) {
		parent::__construct($labels, $name, $padding);
		$this->items = $items;
	}

	public function html() {
		ob_start();
		?>
	<div style="position: relative;padding-top: 1em;text-align: right;" >
		<label for="<?php echo $this->fieldName(); ?>[city]" style="color: red;"><?php echo $this->label[0]; ?>:</label>
		<span style="position: absolute;top: 0;left: 9.5em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[1]; ?>
			</i>
		</span>

		<select name="<?php echo $this->fieldName(); ?>[city]" id="<?php echo $this->fieldName(); ?>[city]"
				style="width: 12em;">
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
		<div style="position: relative;padding-top: 1em;text-align: right;" >
			<span style="position: absolute;top: 0;left: 4.5em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[3]; ?>
			</i>
		</span>
			<span style="position: absolute;top: 0;left: 13em;font-size: 0.8em;">
			<i>
				<?php echo $this->label[4]; ?>
			</i>
		</span>
			<label for="<?php echo $this->fieldName(); ?>[date]"><?php echo $this->label[2]; ?>:</label>
			<script>
				$(function() {
					var name = '<?php echo addcslashes(addcslashes($this->fieldName().'[date]', '[]'), '\\'); ?>';
					$( "#" + name ).datepicker();
				});
			</script>
			<input type="text" id="<?php echo $this->fieldName(); ?>[date]" name="<?php echo $this->fieldName(); ?>[date]" value="<?php echo $this->value['date']; ?>" style="width: 5em;">
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
