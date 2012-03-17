<?php
namespace RealtyImage;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$realtyimage = isset($data['object']) ? $data['object'] : null;
		$data['model']->setTemplate($this);
		?>
	<script type="text/javascript">
		$(function () {
			AdminJS.initTabs('#tabs');
		});
		$(document).ready(function(){
			$("#editForm").validate({
				errorClass: "invalid"
			});
		});
	</script>
	<div class="submenubar">
		<a href="<?php echo $this->getUrl('realtyimage_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('realtyimage_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $realtyimage ? 'Редактирование' : 'Создание';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('realtyimage_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $realtyimage->id ? : ''; ?>"/>

			<div id="general">
				<table>

						<?php
							$dRaw = $data->getRaw();
							foreach($dRaw['model']->fields as $field) {
								if (($field->name == id) || (!$field->isForm)) continue;
								echo '<tr><td>' .$field->adminName. '</td>';
								echo '<td>' .$field->input($realtyimage). '</td></tr>';
							}
						?>

				</table>
			</div>

			<table>
				<tr>
					<td colspan="2">
						<div class="button button-save">
							<div class="icon icon-save"></div>
							<span>Сохранить</span>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php

	}
}