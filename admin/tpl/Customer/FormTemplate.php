<?php
namespace Customer;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$customer = isset($data['object']) ? $data['object'] : null;
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
		<a href="<?php echo $this->getUrl('customer_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('customer_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $customer ? 'Редактирование %OBJECT_NAME%' : 'Создание %OBJECT_NAME%';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('customer_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $customer->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Поле</td>
						<td><input name="form[fieldname]" class="required" minlength="2" value="<?php echo $customer->fieldname; ?>"/></td>
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