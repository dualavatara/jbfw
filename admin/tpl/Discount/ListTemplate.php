<?php

namespace Discount;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
        <?php $this->showLink('[Список]','discount_list');?>
        <?php $this->showLink('[Добавить]','discount_add');?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Описание</th>
			<th>Процент</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('discount_edit'))
                        $this->showLink($item->description,'discount_edit', array('id' => $item->id));
                    else echo $item->description;?>
				</td>
				<td><?php echo $item->percent . '%';?>
				</td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','discount_delete', array('id' => $item->id),
                                       'onClick="return AdminJS.deleteConfirmation();"');?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->getModel()->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}