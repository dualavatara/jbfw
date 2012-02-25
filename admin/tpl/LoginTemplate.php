<?php

use Admin\Extension\Template\Template;

class LoginTemplate extends Template {

	protected function show($data, $content = null) {
		$form   = $data['form'];
		$config = $this->app->getConfig();
		?>
	<html>
	<head>
		<title><?=$config->title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="<?=$config->baseUrl?>/static/admin.css"/>
	</head>
	<body>
		<div style="position: absolute; top: 50%; width: 100%; text-align: center;">
			<form method="post" action="<?php echo $this->getUrl('login');?>" >
				<table style="display: inline;">
					<tr>
						<td>Логин:</td>
						<td><input name="form[login]" type="text" size="20" maxlength="64"
						           value="<?php echo isset($form['login']) ? $form['login'] : '';?>">
						</td>
						<td>Пароль:</td>
						<td><input name="form[password]" type="password" size="20" maxlength="64"></td>
						<td><input type="submit" value="Войти"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
	</html>
	<?php

	}
}