<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 1:04 AM
 */
namespace Ctl;

require_once 'config/config.php';
require_once 'lib/datastorage.media.lib.php';

class StaticCtl  extends BaseCtl{
	public function get($key) {
		try {
			$storage = new \DataStorageMedia('./' . PATH_DATA );

			// Get extensions from key - part from the last dot to the end
			$match = array();
			$extension = '';
			if (preg_match('/\.([^\.]*)$/', $key, $match)) {
				$extension = $match[1];
			}

			$contentType = $storage->getContentType($extension);
			header('Content-Type: '.$contentType);
			return $storage->output($key);
		} catch (\Exception $e) {
		}
	}

	static public function link($method, $params) {
		switch($method) {
			case 'get' : return '/s/' . $params['key'];
			default: throw new \NotFoundException();
		}
	}


}
