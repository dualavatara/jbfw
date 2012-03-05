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
						<td>E-mail</td>
						<td><input name="form[email]" class="required" minlength="2" value="<?php echo $customer->email; ?>"/></td>
					</tr>
					<tr>
						<td>Имя</td>
						<td><input name="form[name]" class="required" minlength="2" value="<?php echo $customer->name; ?>"/></td>
					</tr>
					<tr>
						<td>Тел. Москва</td>
						<td><input name="form[phone_msk]" minlength="2" value="<?php echo $customer->phone_msk; ?>"/></td>
					</tr>
					<tr>
						<td>Тел. местный</td>
						<td><input name="form[phone_local]" minlength="2" value="<?php echo $customer->phone_local; ?>"/></td>
					</tr>
					<tr>
						<td>Адрес</td>
						<td>
							<textarea name="form[address]" cols="40" rows="5"><?php echo $customer->address; ?></textarea></td>
					</tr>
					<tr>
						<td>Страна</td>
						<td><input name="form[country]" minlength="2" value="<?php echo $customer->country; ?>"/></td>
					</tr>
					<tr>
						<td>Skype</td>
						<td><input name="form[skype]" minlength="2" value="<?php echo $customer->skype; ?>"/></td>
					</tr>
					<tr>
						<td>ICQ</td>
						<td><input name="form[icq]" minlength="2" value="<?php echo $customer->icq; ?>"/></td>
					</tr>
					<tr>
						<td>Заметка администратора</td>
						<td>
							<textarea name="form[admin_note]" cols="40" rows="5"><?php echo $customer->admin_note; ?></textarea></td>
						</td>
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