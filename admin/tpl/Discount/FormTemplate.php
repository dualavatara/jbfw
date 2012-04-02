<?php
namespace Discount;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$discount = isset($data['object']) ? $data['object'] : null;
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
		<a href="<?php echo $this->getUrl('discount_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $discount ? 'Редактирование скидки' : 'Создание скидки';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('discount_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $discount->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Описание</td>
						<td><textarea rows="10" cols="40" name="form[description]" class="required" minlength="2" ><?php echo $discount->description; ?></textarea></td>
					</tr>
					<tr>
						<td>Процент</td>
						<td><input name="form[percent]" class="required" minlength="2" value="<?php echo floatval($discount->percent); ?>"/></td>
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