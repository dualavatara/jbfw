<?php

namespace Price;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
		<?php $this->showLink('[Список]', 'price_list')?>
		<?php $this->showLink('[Добавить]', 'price_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Начало</th>
			<th>Конец</th>
			<th>Значение</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model'] as $i => $item): ?>
		<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
			<td><?php
				if ($this->app['user']->checkRoute('price_edit')) $this->showLink($item->id, 'price_edit', array('id' => $item->id)); else echo $item->id; ?></td>
			<td><?php $date = new \DateTime($item->start); echo $date->format('Y-m-d');?></td>
			<td><?php $date = new \DateTime($item->end); echo $date->format('Y-m-d');?></td>
			<td><?php
				echo $item->value . ' ' . $data['currencies'][$item->currency_id];
				?>
			</td>
			<td>
				<?php $this->showLink('&nbsp;X&nbsp;', 'price_delete', array('id' => $item->id), 'onClick="return AdminJS.deleteConfirmation();"');?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}