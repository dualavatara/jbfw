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
        <?php $this->showLink('[Список]','user_list')?>
        <?php $this->showLink('[Добавить]','user_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">Id</th>
			<th>Login</th>
			<th>Name</th>
			<th>Created</th>
			<th width="1%"></th>
		</tr>
		<?php foreach ($data['model'] as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('user_edit'))
                        $this->showLink($item->login,'user_edit', array('id' => $item->id));
                    else echo $item->login;?>
				</td>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->created; ?></td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','user_delete', array('id' => $item->id),
                                       'onClick="return AdminJS.deleteConfirmation();"');?>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}