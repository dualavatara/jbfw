<?php
namespace Beach;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$beach = isset($data['object']) ? $data['object'] : null;
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
		<?php $this->listLink(); ?>
		<a href="<?php echo $this->getUrl('beach_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $beach ? 'Редактирование пляжа' : 'Создание пляжа';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('beach_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $beach->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Название</td>
						<td><input name="form[name]" class="required" minlength="2" value="<?php echo $beach->name; ?>"/></td>
					</tr>
					<tr>
						<td>Сслыка на описание</td>
						<td><input name="form[link]" class="required" minlength="2" value="<?php echo $beach->link; ?>"/></td>
					</tr>
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