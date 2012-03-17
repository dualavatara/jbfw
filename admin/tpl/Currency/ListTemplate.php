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
			<?php
			foreach ($data['model']->getModel()->fields as $field) {
				$w = $field->is(\Field::ADMIN_LIST_MINW) ? ' width="1%"' : '';
				if ($field->is(\Field::ADMIN_LIST)) echo '<th' . $w . '>' . $field->adminName. '</th>';
			}
			?>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
		<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
			<?php
			foreach ($data['model']->getModel()->fields as $field) {
				if (!$field->is(\Field::ADMIN_LIST)) continue;
				echo '<td>';
				if ($field->is(\Field::ADMIN_LIST_EDIT)) {
					if ($this->app['user']->checkRoute('currency_edit'))
						$this->showLink($item->{$field->name}, 'currency_edit', array('id' => $item->id));
					else echo $item->{$field->name};
				} else echo $item->{$field->name};
				echo '</td>';
			}
			?>
			<td>
				<?php $this->showLink('&nbsp;X&nbsp;','currency_delete', array('id' => $item->id),
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
