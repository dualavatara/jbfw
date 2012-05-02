<?php
/**
 * User: dualavatara
 * Date: 5/1/12
 * Time: 2:45 PM
 */
namespace View;

class PlacesView extends BaseView {
	public $places;


	public function show() {
		$this->start();
		?>
	<?php
		foreach ($this->places as $place) {
		//	$selected = ($k == $this->value['place']) ? 'selected' : '';
			?>
		<option value="<?php echo $place->id; ?>"><?php echo $place->name; ?></option>
		<?php
		};
		?>
	<?php
		$this->end();
		return parent::show();
	}
}
