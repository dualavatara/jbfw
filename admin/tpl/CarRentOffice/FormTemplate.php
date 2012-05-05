<?php
namespace CarRentOffice;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$carrentoffice = isset($data['object']) ? $data['object'] : null;
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
		<a href="<?php echo $this->getUrl('carrentoffice_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $carrentoffice ? 'Редактирование' : 'Создание';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('carrentoffice_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $carrentoffice->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Название</td>
						<td><input name="form[name]" class="required" minlength="2" value="<?php echo $carrentoffice->name; ?>"/></td>
					</tr>
					<tr>
						<td>Описание</td>
						<td><textarea cols="40" rows="10" name="form[description]" ><?php echo $carrentoffice->description; ?></textarea></td>
					</tr>
					<tr>
						<td>Ссылка на правила аренды</td>
						<td><input name="form[rent_rules_link]" class="required" minlength="2" value="<?php echo $carrentoffice->rent_rules_link; ?>"/></td>
					</tr>
					<tr>
						<td>Клиент</td>
						<td><?php
							$this->insertTemplate('Form\SearchSelectField', array(
								'name'		=> 'form[customer_id]',
								'value'		=> $carrentoffice->customer_id,
								'display_value' => $data['customers'][$carrentoffice->customer_id],
								'rest_url' => '/admin/customer/json'
							)); ?></td>
					</tr>
					<tr>
						<td>Рейтинг</td>
						<td><input name="form[rating]" value="<?php echo intval($carrentoffice->rating); ?>"/></td>
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