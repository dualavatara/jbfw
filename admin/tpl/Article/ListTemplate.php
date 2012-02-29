<?php

namespace Article;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
		<?php $this->showLink('[Список]', 'article_list')?>
		<?php $this->showLink('[Добавить]', 'article_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Название</th>
			<th>Дата создания</th>
			<th>Сортировка</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
		<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
			<td><?php
				if ($this->app['user']->checkRoute('article_edit')) $this->showLink($item->id, 'article_edit', array('id' => $item->id)); else echo $item->id; ?>
			</td>
			<td><?php if ($this->app['user']->checkRoute('article_edit')) $this->showLink($item->name, 'article_edit', array('id' => $item->id)); else echo $item->name; ?></td>
			<td><?php echo $item->created; ?></td>
			<td><?php echo $item->ord; ?>
			</td>
			<td>
				<?php $this->showLink('&nbsp;X&nbsp;', 'article_delete', array('id' => $item->id), 'onClick="return AdminJS.deleteConfirmation();"');?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->getModel()->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}