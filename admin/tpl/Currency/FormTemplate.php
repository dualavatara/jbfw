<?php

namespace Currency;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$currency = isset($data['object']) ? $data['object'] : null;
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
		<a href="<?php echo $this->getUrl('currency_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('currency_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $currency ? 'Редактирование валюты' : 'Создание валюты';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2" action="<?php echo $this->getUrl('currency_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $currency->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Название</td>
						<td><input name="form[name]" class="required" minlength="2" value="<?php echo $currency->name; ?>"/></td>
					</tr>
					<tr>
						<td>Обозначение</td>
						<td><input type="sign" name="form[sign]" class="required" value="<?php echo $currency->sign; ?>"/></td>
					</tr>
					<tr>
						<td>Курс</td>
						<td><input name="form[course]" class="required" value="<?php echo floatval($currency->course); ?>"/></td>
					</tr>
				</table>
			</div>

			<table>
				<tr>
					<td colspan="2">
						<div class="button button-save">
							<div class="icon icon-save"></div>
							<span>Save</span>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php

	}
}