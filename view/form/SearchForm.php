<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 4:41 PM
 */

namespace View\Form;

class SearchForm implements IForm {
	/**
	 * @var string Form name, used in params decoration as formname[paramname]
	 */
	public $name;

	/**
	 * @var array
	 */

	public $fieldsMain;

	/**
	 * @var array
	 */

	public $fieldsExt;

	public $action;
	/**
	 * @param $name Form name, used in params decoration as formname[paramname]
	 */
	public function __construct($name, $action) {
		$this->name = $name;
		$this->action = $action;
		$this->fieldsMain = array();
		$this->fieldsExt = array();
	}

	public function add(Field $field, $main = false) {
		$field->formname = $this->name;

		if (isset($_REQUEST[$this->name][$field->name])) $field->value = $_REQUEST[$this->name][$field->name];

		if ($main) $this->fieldsMain[] = $field;
		else $this->fieldsExt[] = $field;
	}

	public function html() {
		ob_start();
		if (isset($_REQUEST[$this->name]) && $_REQUEST[$this->name]['ext']) $ext = "display:block;";
		else $ext = "display:none;";
		?>
		<script>
			function <?php echo $this->name;?>ToggleExt() {
				var name = '<?php echo $this->name; ?>';
				$('#exsearch_' + name).toggle();
				var h = '#' + '<?php echo $this->name . '\\\\[ext\\\\]'; ?>';
				var submit = '#' + '<?php echo $this->name . '\\\\[submit\\\\]'; ?>';
				if ($('#exsearch_' + name).css('display') == 'none') {
					//move submit elements to the end of main form
					$(submit).parent().appendTo($('#mainsearch_' + name));
					$(h).val('0');

				}
				else {
					//move submit elements to the end of extended form
					//$(submit).detach();
					$(submit).parent().appendTo($('#exsearch_' + name));
					$(h).val('1');
				}
			}

			$(function () {
				var name = '<?php echo $this->name; ?>';
				$('#exsearch_fold_' + name).click(function () {
						<?php echo $this->name;?>ToggleExt();
				})
					<?php
		if (isset($_REQUEST[$this->name]) && $_REQUEST[$this->name]['ext']) echo $this->name . "ToggleExt()";
				?>
			})
		</script>
		<div id="search_form_<?php echo $this->name; ?>">
			<form action="<?php echo $this->action; ?>" method="GET" enctype="application/x-www-form-urlencoded"
				  name="<?php echo $this->name; ?>">
				<input type="hidden" value="<?php echo $this->name; ?>" name="form">
				<div class="searchcontent" id="mainsearch_<?php echo $this->name; ?>" style="text-align: left; padding: 1em 1em 0 1em;">

					<?php
					foreach($this->fieldsMain as $field)  {
						if ($field->padding) $p = 'style="padding:'.$field->padding.';"'; else $p ='';
						echo '<div class="formfield" '.$p.'>' . $field->html() . '</div>';
					}
					?>

				</div>
				<div class="exsearch" id="exsearch_<?php echo $this->name; ?>" style="display:none; text-align: left; padding: 1em;">
					<input type="hidden"
						   name="<?php echo $this->name . '[ext]'; ?>"
						   id="<?php echo $this->name . '[ext]'; ?>"
						   value="<?php echo $_REQUEST[$this->name]['ext']; ?>">
					<?php
					foreach($this->fieldsExt as $field) {
						if ($field->padding) $p = 'style="padding:'.$field->padding.';"'; else $p ='';
						echo '<div class="formfield" '.$p.'>' . $field->html() . '</div>';
					}
					?>
				</div>
				<div class="exsearch_fold" id="exsearch_fold_<?php echo $this->name; ?>">
					<a class="white" href="javascript:void(0);">дополнительные параметры поиска</a>
				</div>
			</form>
		</div>
	<?php
		return ob_get_clean();
	}
}
