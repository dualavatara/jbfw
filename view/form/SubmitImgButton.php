<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 9:13 PM
 */

namespace View\Form;

class SubmitImgButton extends Field {
	public $imgSrc;
	public function __construct($imgSrc, $padding = false) {
		parent::__construct('', 'submit', $padding);
		$this->imgSrc = $imgSrc;
	}

	public function html() {
		ob_start();
		?>

	<img src="<?php echo $this->imgSrc; ?>" onclick="submit('<?php echo $this->formname; ?>')" class="imgbutton" id=<?php echo $this->fieldName(); ?>>
	<?php
		return ob_get_clean();
	}
}
