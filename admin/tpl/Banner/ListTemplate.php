<?php

namespace Banner;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$types = $data['model']->getModel()->getTypes();
		$flags = $data['model']->getModel()->getFlags();
		?>
	<div class="submenubar">
        <?php $this->showLink('[Список]','banner_list')?>
        <?php $this->showLink('[Добавить]','banner_add')?>
	</div>
	<table class="list">
		<tr>
			<th width="1%">id</th>
			<th>Тип</th>
			<th>Изображение</th>
			<th>Флаги</th>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<td><?php echo $item->id; ?></td>
				<td><?php
				$type = isset($types[$item->type]) ? $types[$item->type] : 'нет';

                    if($this->app['user']->checkRoute('banner_edit'))
                        $this->showLink($type,'banner_edit', array('id' => $item->id));
                    else echo $type;?>
				</td>
				<td>
					<?php if ($item->image) $this->showLink($item->image, 'static', array('key' => $item->image),'class="lightbox" target="_blank"')?>
				</td>
				<td><?php
					$flag = array();
						 foreach($flags as $k => $v) if ($item->flags->check($k)) $flag[] = $v;
					 echo implode(',', $flag);?>
				</td>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','banner_delete', array('id' => $item->id),
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