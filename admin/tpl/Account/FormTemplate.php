<?php

namespace Account;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$account = $data['model'];
		?>
	<div class="submenubar">
        <?php $this->showLink('[Список]','account_list')?>
        <?php $this->showLink('[Добавить]','account_add')?>
	</div>
	<div class="group">
		<div class="capture"><?php echo $account ? 'Редактирование пользователя' : 'Создание пользователя';?></div>
		<form method="post" action="<?php echo $this->getUrl('account_save'); ?>">
			<input type="hidden" name="form[id]" value="<?php echo $account->id; ?>" />
			<table>
				<!--tr>
					<td>UID</td>
					<td><input name="form[uid]" value="<?php// echo $account->uid; ?>"/></td>
				</tr-->
				<tr>
					<td>Devices</td>
					<td><?php
						$this->insertTemplate('Form\SelectField', array(
							'name'     => 'device',
							'values'   => $data['devices'],
							'selected' => '',
						    'empty'    => true,
						)); ?></td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td><input name="form[email]" value="<?php echo $account->email; ?>"/></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input name="form[password]" value="<?php echo $account->password; ?>"/></td>
				</tr>
				<tr>
					<td>Linked application</td>
					<td><?php $this->insertTemplate('Form\RelationField', array(
							'name' => 'linked',
							'all' => $data['applications'],
							'related' => $data['linked'],
							'value_field' => 'consumer_key',
							'text_field' => 'title'
						)); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="button button-save">
							<div class="icon icon-save"></div>
							<span>Save</span>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php

	}
}