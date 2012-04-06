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
			if (strstr($contentType, 'image') && ($_REQUEST['w'] || $_REQUEST['h'])) {
				$file = $storage->getFileName(quoted_printable_decode($key));
				$image = new \Imagick($file);
				$w = $image->getimagewidth();
				$h = $image->getimageheight();
				//normalize params
				$_REQUEST['w'] = $_REQUEST['w'] > $w ? $w : $_REQUEST['w'];
				$_REQUEST['h'] = $_REQUEST['h'] > $h ? $h : $_REQUEST['h'];
				$_REQUEST['w'] = !$_REQUEST['w'] ? $_REQUEST['h'] : $_REQUEST['w'];
				$_REQUEST['h'] = !$_REQUEST['h'] ? $_REQUEST['w'] : $_REQUEST['h'];

				//calculate crop size

				$aw = $w / floatval($_REQUEST['w']);//во сколько раз запрошенное меньше ширины
				$ah = $h / floatval($_REQUEST['h']);//во сколько раз запрошенное меньше высоты
				if ($aw < $ah){
					//$image->chopimage($w, $h*$aw, 0, 0);
					$nw = $w;
					$nh = ($h / $ah)*$aw;
				}
				else {
					//$image->chopimage($w * $ah, $h, 0, 0);
					$nw =($w / $aw) * $ah;
					$nh = $h;
				}
				$image->cropImage($nw, $nh, 0, 0);
				$image->thumbnailImage($_REQUEST['w'], $_REQUEST['h']);
				$image->setImageFormat('png');

				/* Output the image with headers */
				header('Content-type: image/png');
				echo $image;
			} else	return $storage->output($key);
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
