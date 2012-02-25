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
        	<a href="#profile">Profile</a>
            <a href="#access">Access</a>
        </div>
        <form method="post" action="<?php echo $this->getUrl('user_save'); ?>" enctype="multipart/form-data">
			<input type="hidden" name="form[id]" value="<?php echo $user->id ?: ''; ?>"/>
            <div id="profile">
                <table>
                    <tr>
                        <td>Login</td>
                        <td><input name="form[login]" value="<?php echo $user->login; ?>"/></td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td><input type="password" name="form[password]" value=""/></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><input name="form[name]" value="<?php echo $user->name; ?>" /></td>
                    </tr>
                </table>
            </div>
            <div id="access">
                <?php foreach($data['routes'] as $name_controller => $controller): ?>
                    <p><?php echo $name_controller; ?></p>
                    <?php foreach($controller as $route_name => $action): ?>
                        <div class="user_access">
	                        <input type="checkbox" name="form[routes][<?php echo $route_name; ?>]"
                                <?php echo in_array($route_name, $data['access']->getRaw()) ? 'checked' : ''; ?> />
	                        <?php echo $action; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="user_access_clear"></div>
	            <?php endforeach; ?>
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