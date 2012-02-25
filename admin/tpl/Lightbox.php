<?php

use Admin\Extension\Template\Template;

class Lightbox extends Template {
	
	function show($data, $content = null) {
			$config = $this->app->getConfig();
		?>
		<link rel="stylesheet" type="text/css" href="<?=$config->baseUrl?>/static/lightbox/css/jquery.lightbox-0.5.css" media="screen"/>
		<script type="text/javascript" src="<?=$config->baseUrl?>/static/lightbox/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript">
			$(function(){
				var base = '<?php echo $config->baseUrl; ?>';
				var image_path = base + '/static/lightbox/images/';
	
				$('a.lightbox').lightBox({
					imageLoading:  image_path + 'lightbox-icoloading.gif',
					imageBtnClose: image_path + 'lightbox-btn-close.gif',
					imageBlank:    image_path + 'lightbox-blank.gif',
					imageBtnPrev:  image_path + 'lightbox-btn-prev.gif',
					imageBtnNext:  image_path + 'lightbox-btn-next.gif'
				});
			});
		</script>
	<?
	}
}