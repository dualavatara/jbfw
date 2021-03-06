<?php

namespace CarRentOffice;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
        <?php $this->listLink();?>
        <?php $this->showLink('[Добавить]','carrentoffice_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Название</th>
			<th>Процент залога</th>
			<th>Контактное лицо</th>
			<th>Рейтинг</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('carrentoffice_edit'))
                        $this->showLink($item->name,'carrentoffice_edit', array('id' => $item->id));
                    else echo $item->name;?>
				</td>
				<td><?php echo $item->percent;?></td>
				<td><?php echo $data['customers'][$item->customer_id];?></td>
				<td><?php echo $item->rating;?></td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','carrentoffice_delete', array('id' => $item->id),
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