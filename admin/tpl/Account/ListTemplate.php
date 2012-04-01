<?php

namespace Account;

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
        <?php $this->showLink('[Добавить]','account_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">Id</th>
			<th>Nick</th>
			<th>E-mail</th>
			<th>Password</th>
			<th>FB Email</th>
			<th>EmailErrorsCounter</th>
			<th width="1%"></th>
		</tr>
		<?php foreach ($data['model'] as $i => $item): ?> 
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td>
                    <?php if($this->app['user']->checkRoute('account_edit'))
                        $this->showLink($item->id,'account_edit', array('id' => $item->id));
                    else echo $item->id;?>
				</td>
				<td><?php echo $item->nick; ?></td>
				<td><?php echo $item->email; ?></td>
				<td><?php echo $item->password; ?></td>
				<td><?php echo $item->fb_email; ?></td>
				<td><?php echo $item->emailErrors; ?></td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;', 'account_delete', array('id' => $item->id),
                        'onClick="return AdminJS.deleteConfirmation();" ')?>
                </td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php if (0 == $data['model']->count()): ?>
		<div class="list-empty">Список пуст!</div>
		<?php endif; ?>
	<?php

	}
}