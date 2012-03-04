<?php

namespace Customer;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		?>
	<div class="submenubar">
        <?php $this->showLink('[Список]','customer_list')?>
        <?php $this->showLink('[Добавить]','customer_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Имя</th>
			<th>E-mail</th>
			<th>Тел. Москва</th>
			<th>Тел. местный</th>
			<th>Заметка администратора</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('customer_edit'))
                        $this->showLink($item->name,'customer_edit', array('id' => $item->id));
                    else echo $item->name;?>
				</td>
				<td>
					<a href="mailto:<?php echo $item->email; ?>"><?php echo $item->email; ?></a>
				</td>
				<td><?php echo $item->phone_msk; ?></td>
				<td><?php echo $item->phone_local; ?></td>
				<td><?php echo $item->admin_note; ?></td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','customer_delete', array('id' => $item->id),
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