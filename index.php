<?
require_once 'swift_required.php';
require_once 'lib/JBFWClassLoader.php';

require_once 'config/config.php';
require_once 'lib/dicontainer.lib.php';
require_once 'lib/logger.lib.php';

JBFWClassLoader::addException('/admin/i');


/*
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
		if ($errno == E_ERROR || $errno == E_USER_ERROR) {
			header('HTTP/1.1 500 Internal Server Error');
		}
		Logger::obj()->nativeLog($errno, $errstr);
		return true;
}, E_ALL + E_STRICT);

try {*/
session_start();
if (isset($_SESSION['user']) and in_array('closed_index',$_SESSION['routes'])) {
function vardump($obj) {
var_dump($obj);
};
} else {
function vardump($obj) {
}
}

	$di = new DIContainer();
	$disp = $di->Dispatcher();
	Settings::obj()->set($di->SettingModel());
	UrlAliases::obj()->set($di->UrlAliasesModel());
if (Settings::obj()->get()->getClosed() and (!isset($_SESSION['user']) or !in_array('closed_index',$_SESSION['routes']))) {
	require('static/html/maitenance.html');
	return;
} else {
	try {
	$disp->main();
	} catch (\NotFoundException $e) {
		header('HTTP/1.1 404 Not Found');
		$_SERVER['REQUEST_URI'] = Settings::obj()->get()->get404();
		$disp->main();
	}
}
/*} catch (Exception $e) {
	header('HTTP/1.1 500 Internal Server Error');
	Logger::obj()->error($e->getMessage());
}*/
?>
