<?php
/**
 * User: dualavatara
 * Date: 3/12/12
 * Time: 1:04 AM
 */
require_once 'config/config.php';
require_once 'lib/datastorage.media.lib.php';
require_once 'ctl/Ctl.php';

class StaticCtl  extends Ctl{
	public function get($key) {
		try {
			$storage = new DataStorageMedia('./' . PATH_DATA );

			// Get extensions from key - part from the last dot to the end
			$match = array();
			$extension = '';
			if (preg_match('/\.([^\.]*)$/', $key, $match)) {
				$extension = $match[1];
			}

			//$contentType = $storage->getContentType($extension);
			return $storage->output($key);
		} catch (\Exception $e) {
		}
	}
}
