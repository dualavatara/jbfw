<?php

namespace Article;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$model = $data['model'];
		$article = isset($data['object']) ? $data['object'] : null;
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
		<a href="<?php echo $this->getUrl('article_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('article_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $article ? 'Редактирование статьи' : 'Создание статьи';?></div>
		<div id="tabs">
			<a href="#general">Общие</a>
		</div>
		<form method="post" id="editForm" class="required" minlength="2"
			  action="<?php echo $this->getUrl('article_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $article->id ? : ''; ?>"/>

			<div id="general">
				<table>
					<tr>
						<td>Название</td>
						<td><input name="form[name]" class="required" value="<?php echo $article->name; ?>"/></td>
					</tr>
					<tr>
						<td>Тип</td>
						<td><?php
							$this->insertTemplate('Form\SelectField', array(
								'name'     => 'type',
								'values'   => $data['types'],
								'selected' => $article->type,
								'empty'    => false,
							)); ?>
						</td>
					</tr>
					<tr>
						<td>Текст статьи(короткий)</td>
						<td>
							<textarea rows="10" cols="40" name="form[content_short]"><?php echo $article->content_short; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Текст статьи</td>
						<td>
							<textarea rows="40" cols="40" name="form[content]"><?php echo $article->content; ?></textarea>
						</td>
					</tr>

					<tr>
						<td>Картинка(маленькая)</td>
						<td>
							<?php
							$this->insertTemplate('Form\ImageField', array(
								'name'     => 'photo_preview',
								'key'   => $article->photo_preview,
							));
							?>
						</td>
					</tr>
					<tr>
						<td>Картинка</td>
						<td>
							<?php
							$this->insertTemplate('Form\ImageField', array(
								'name'     => 'photo',
								'key'   => $article->photo,
							));
?>
						</td>
					</tr>
					<tr>
						<td>Дата создания</td>
						<td>
							<?php
							$date = array('name'  => 'created',
										  'value' => $article->created);
							$this->insertTemplate('Form\DateTimeField', $date);
							?>
						</td>
					</tr>

					<tr>
						<td>&nbsp;</td>
						<td><?php
							$this->insertTemplate('Form\FlagsField', array(
								'title'     => 'Опции',
								'name'		=> 'form[flags]',
								'value'		=> $article->flags,
								'flags'		=> $model->getModel()->getFlags()
							)); ?>
						</td>
					</tr>
					<tr>
						<td>Вес сортировки</td>
						<td><input name="form[ord]" class="required" value="<?php echo $article->ord; ?>"/></td>
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