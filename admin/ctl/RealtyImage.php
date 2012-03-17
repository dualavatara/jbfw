<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class RealtyImage extends \Admin\StdController {
	public function __construct(\Admin\Application $app) {
		parent::__construct('modules', 'realtyimage_list', 'RealtyImage', $app);
	}

}