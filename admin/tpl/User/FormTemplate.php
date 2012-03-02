<?php

namespace User;

use Admin\Extension\Template\Template;

class FormTemplate extends Template {

	public function __construct($app) {
		parent::__construct($app);
		$this->setParent('Layout');
	}

	protected function show($data, $content = null) {
		$user = $data['model'];
		?>
    <script type="text/javascript">
    		$(function(){
    			AdminJS.initTabs('#tabs');
    		});
    </script>
	<div class="submenubar">
		<a href="<?php echo $this->getUrl('user_list') ?>">[Список]</a>
		<a href="<?php echo $this->getUrl('user_add') ?>">[Добавить]</a>
	</div>
	<div class="group">
		<div class="capture"><?php echo $user ? 'Редактирование пользователя' : 'Создание пользователя';?></div>
        <div id="tabs">
        	<a href="#profile">Профиль</a>
            <a href="#access">Доступ</a>
        </div>
        <form method="post" action="<?php echo $this->getUrl('user_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $user->id ?: ''; ?>"/>
            <div id="profile">
                <table>
                    <tr>
                        <td>Логин</td>
                        <td><input name="form[login]" value="<?php echo $user->login; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Новый пароль</td>
                        <td><input type="password" name="form[password]" value=""/></td>
                    </tr>
                    <tr>
                        <td>Имя</td>
                        <td><input name="form[name]" value="<?php echo $user->name; ?>" /></td>
                    </tr>
                </table>
            </div>
            <div id="access">

					<input type="checkbox" id="allcheck" onchange="onAllChange('allcheck');" />
					<label for="allcheck">Все</label>
                <?php $i = 0;
				$k = 0;
				foreach($data['routes'] as $name_controller => $controller):
					$i++;
					?>
					<fieldset>
                    <legend>
						<input type="checkbox" id="allcheck_<?php echo $i;?>"
							   onchange="onAllChange('allcheck\\_<?php echo $i;?>', 'allcheck');"
							/>
						<label for="allcheck_<?php echo $i;?>" ><?php echo $name_controller; ?></label></legend>
                    <?php
					foreach($controller as $route_name => $action):
						$k++;
					?>
                        <div class="user_access">
	                        <input type="checkbox" id="allcheck_<?php echo $i;?>_<?php echo $k;?>"
								   name="form[routes][<?php echo $route_name; ?>]"
								   onchange="checkParent('allcheck\\_<?php echo $i;?>');checkParent('allcheck');"
								<?php if(in_array($route_name, $data['access']->getRaw())) echo 'checked';  ?>
								/>
							<label for="allcheck_<?php echo $i;?>_<?php echo $k;?>" ><?php echo $action; ?></label>
                        </div>
                    <?php endforeach; ?>
                    <div class="user_access_clear"></div>
						<script type="text/javascript">checkParent('allcheck\\_<?php echo $i;?>');</script>
					</fieldset>
	            <?php endforeach; ?>
				<script type="text/javascript">checkParent('allcheck');</script>
            </div>
            <table>
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