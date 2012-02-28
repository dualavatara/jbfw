<?php

use Admin\Extension\Template\Template;

class Layout extends Template {

	function show($data, $content = null) {
		$config = $this->app->getConfig();
		?>
	<html>
	<head>
		<title><?=$config->title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="<?=$config->baseUrl?>/static/admin.css"/>
		<link rel="stylesheet" href="<?=$config->baseUrl?>/static/jquery/smoothness/jquery-ui-1.8.17.custom.css"/>
		<script src="<?=$config->baseUrl?>/static/jquery/jquery.js"></script>
		<script src="<?=$config->baseUrl?>/static/jquery/jquery-ui-1.8.17.custom.min.js"></script>
		<script src="<?=$config->baseUrl?>/static/admin.js"></script>
		<script src="<?=$config->baseUrl?>/static/jquery/jqval/jquery.validate.js"></script>
		<script src="<?=$config->baseUrl?>/static/jquery/jqval/localization/messages_ru.js"></script>

		<style type="text/css">
			label.invalid { float: none; color: red; padding-left: .5em; vertical-align: middle; }
		</style>
	</head>
	<body>
	<table style="width: 100%; height: 100%;">
		<tr>
			<td colspan="2" class="header">
				<div class="topTitle"><?php echo $config->title ?></div>
				<div class="userInfo">
					<span>Вы зашли как</span>
					<span class="userName"><?php echo $this->app['user']->login; ?></span>
					<a href="<?php echo $this->getUrl('logout'); ?>">(Выйти)</a>
				</div>
			</td>
		</tr>
		<tr>
			<td class="leftColumn">
				<?php $this->insertTemplate('LeftMenuTemplate', $data); ?>
			</td>
			<td class="rightColumn">
				<?php $this->insertTemplate('SectionsMenuTemplate', $data); ?>
				<div>
					<?php print $content; ?>
				</div>
			</td>
		</tr>
	</table>
	</body>
	</html>
	<?php

	}
}