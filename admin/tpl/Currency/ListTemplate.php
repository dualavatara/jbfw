<?php

namespace Currency;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
        <?php $this->showLink('[Список]','currency_list')?>
        <?php $this->showLink('[Добавить]','currency_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Название</th>
			<th>Обозначение</th>
			<th>Курс</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('currency_edit'))
                        $this->showLink($item->name,'currency_edit', array('id' => $item->id));
                    else echo $item->name;?>
				</td>
				<td><?php echo $item->sign; ?></td>
				<td><?php echo $item->course; ?></td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','currency_delete', array('id' => $item->id),
                                       'onClick="return AdminJS.deleteConfirmation();"');?>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->getModel()->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}