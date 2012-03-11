define('PATH_DATA', 'static/data');

define('MAITENANCE_LOCK', false);

define('DB_NAME', '<?php echo $dbname; ?>');
define('DB_USER', '<?php echo $dbuser; ?>');
define('DB_PASS', '<?php echo $dbpass; ?>');
define('DB_CHARSET', '<?php echo $dbcharset; ?>');
define('DB_DSN', 'mysql:host=<?php echo $dbhost; ?>;dbname='.DB_NAME);

//defaults
define('DEFAULT_LANG', 'ru');
define('DEFAULT_CURRENCY', 1);

// reporting
error_reporting(E_ALL ^ E_NOTICE);
