<?php

namespace Resort;

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
        <?php $this->showLink('[Добавить]','resort_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Название</th>
			<th>Ссылка на описание</th>
			<th>Google maps</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
                    if($this->app['user']->checkRoute('resort_edit'))
                        $this->showLink($item->name,'resort_edit', array('id' => $item->id));
                    else echo $item->name;?>
				</td>
				<td>
					<a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->link; ?></a>
				</td>
				<td>
					<a href="<?php echo $item->gmaplink; ?>" target="_blank">maps.google.com</a>
				</td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','resort_delete', array('id' => $item->id),
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