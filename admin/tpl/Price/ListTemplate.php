<?php

namespace Price;

use Admin\Extension\Template\Template;

class ListTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$data['model']->setTemplate($this);
		?>
	<div class="submenubar">
		<?php if ($_SESSION['urlparams']['from_route']) $this->showLink('[К родителю]',$_SESSION['urlparams']['from_route'], array(), '', true)?>
        <?php $this->showLink('[Список]','price_list')?>
        <?php $this->showLink('[Добавить]','price_add')?>
	</div>
	<table class="list">
		<tr>
			<?php
			$dRaw = $data->getRaw();
			foreach ($dRaw['model']->fields as $field) {
				$w = $field->isMinWidth ? ' width="1%"' : '';
				if ($field->isList) echo '<th' . $w . '>' . $field->adminName. '</th>';
			}
			?>
			<th width="1%">&nbsp;</th>
		</tr>
		<?php foreach ($data['model']->getModel() as $i => $item): ?>
			<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>">
				<?php
					foreach ($dRaw['model']->fields as $field) {
						if (!$field->isList) continue;
						echo '<td>';
						if (($field->isListEdit) && ($this->app['user']->checkRoute('price_edit'))) {
							$this->showLink($field->listText($item), 'price_edit', array('id' => $item->id));
						} else  echo $field->listText($item);
						echo '</td>';
					}
				?>
				<td>
                    <?php $this->showLink('&nbsp;X&nbsp;','price_delete', array('id' => $item->id),
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