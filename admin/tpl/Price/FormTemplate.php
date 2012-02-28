<?php

namespace Price;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$price = $data['model'];
		?>
	<script type="text/javascript">
		$(function () {
			AdminJS.initTabs('#tabs');
		});
		$(document).ready(function () {
			$("#editForm").validate({
				errorClass:"invalid"
			});
		});
	</script>
	<div class="submenubar">
		<a href="<?php echo $this->getUrl('price_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('price_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $price ? 'Редактирование цены' : 'Создание цены';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2"
			  action="<?php echo $this->getUrl('price_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $price->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Начало</td>
						<td>
							<?php
							$date = array('name'  => 'start',
										  'value' => $price->start);
							$this->insertTemplate('Form\DateField', $date);
							?>
						</td>
					</tr>
					<tr>
						<td>Конец</td>
						<td>
							<?php
							$date = array('name'  => 'end',
										  'value' => $price->end);
							$this->insertTemplate('Form\DateField', $date);
							?>
						</td>
					</tr>
					<tr>
						<td>Валюта</td>
						<td><?php
							$this->insertTemplate('Form\SelectField', array(
								'name'     => 'currency_id',
								'values'   => $data['currencies'],
								'selected' => $price->currency_id,
								'empty'    => false,
							)); ?>
						</td>
					</tr>
					<tr>
						<td>Цена</td>
						<td><input name="form[value]" class="required"
								   value="<?php echo floatval($price->value); ?>"/></td>
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