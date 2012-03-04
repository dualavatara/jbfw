<?
require_once 'config/config.php';

if (defined('MAITENANCE_LOCK') && MAITENANCE_LOCK) {
	require('static/html/maitenance.html');
	return;
}

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
