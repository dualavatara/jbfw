<?php
namespace Banner;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$model = $data['model'];
		$banner = isset($data['object']) ? $data['object'] : null;
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
		<?php $this->listLink(); ?>
		<a href="<?php echo $this->getUrl('banner_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $banner ? 'Редактирование баннера' : 'Создание создание';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2"
			  action="<?php echo $this->getUrl('banner_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $banner->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Ссылка</td>
						<td><input type="text" name="form[link]" class="required" value="<?php echo $banner->link ? : 'http://'; ?>"/></td>
					</tr>
					<tr>
						<td>Тип</td>
						<td><?php
							$this->insertTemplate('Form\SelectField', array(
								'name' => 'type',
								'values' => $data['types'],
								'selected' => $banner->type,
								'empty' => false,
							)); ?>
						</td>
					</tr>
					<tr>
						<td>Изображение</td>
						<td>
							<?php
							$this->insertTemplate('Form\ImageField', array(
								'name' => 'image', 'key' => $banner->image,
							));
							?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php
							$this->insertTemplate('Form\FlagsField', array(
								'title' => 'Опции',
								'name' => 'form[flags]',
								'value' => $banner->flags,
								'flags' => $model->getModel()->getFlags()
							)); ?>
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