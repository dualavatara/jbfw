<?
require_once('lib/dicontainer.lib.php');

function translate($str, $lang = false) {
	if (!$lang) $lang = LANG_ID ? LANG_ID : DEFAULT_LANG_ID;
	$di = DIContainer::obj();
	return $di->Language()->translate($str, $lang);
}
?>