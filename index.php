<?
//test pricemodel branch
//phpinfo();
require_once('lib/dicontainer.lib.php');
require_once('lib/logger.lib.php');

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
		if ($errno == E_ERROR || $errno == E_USER_ERROR) {
			header('HTTP/1.1 500 Internal Server Error');
		}
		Logger::obj()->nativeLog($errno, $errstr);
		return true;
}, E_ALL + E_STRICT);

try {
	$di = new DIContainer();
	$disp = $di->Dispatcher();
	$disp->main();
} catch (Exception $e) {
	header('HTTP/1.1 500 Internal Server Error');
	Logger::obj()->error($e->getMessage());
}
?>
