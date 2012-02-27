<?php

require_once 'config/config.php';

return array(

	/* Main parameters, all required */
	'baseUrl' => '/admin',
	'rootPath' => '.',
	'ctlPath' => 'ctl/',
	'title' => 'Администрирование',

	'dataPath' => '../' . PATH_DATA,
	
	/* Extension's options start */
	'db.options' => array(
//		'host' => DB_HOST,
//		'port' => DB_PORT,
		'user' => DB_USER,
		'pass' => DB_PASS,
//		'name' => DB_NAME,
		'dsn' => DB_DSN,
		'charset' => DB_CHARSET,
		//'class' => '\Admin\Extension\Database\Database',
	),
	
	'template.options' => array(
		'path' => 'tpl/'	
	),
	
	'security.options' => array(
		'login_route' => 'login',
		'session_key' => 'user',
		'class' => 'SecurityUser',
        'logout_route' => 'logout',
	),
	/* End */

	'routes' => array(
		'home'         => array('/', 'User', 'list'),
		
		'login'        => array('/login',   'Auth', 'login'),
		'logout'       => array('/logout',  'Auth', 'logout'),
		
		'static'       => array('/s/{key}', 'Data', 'get'),
		
		'account_edit' => array('/account/edit/{id}',       'Account', 'edit'),
		'account_list' => array('/account/list',            'Account', 'list'),
        'account_add' => array('/account/add',              'Account', 'add'),
		'account_delete' => array('/account/delete/{id}',   'Account', 'delete'),
		'account_save' => array('/account/save',            'Account', 'save'),

		'user_edit'    => array('/user/edit/{id}',      'User', 'edit'),
		'user_delete'  => array('/user/delete/{id}',    'User', 'delete'),
		'user_list'    => array('/user/list',           'User', 'list'),
		'user_save'    => array('/user/save',           'User', 'save'),
		'user_add'     => array('/user/add',            'User', 'add'),
	),
	
	'menu' => array(
		'modules' => array(
			'title' => 'Modules',
			'sections' => array(
				'account_list' => array('title' => 'Accounts',     'route' => 'account_list',   'params' => array()),
			)
		),
		'sys' => array(
			'title' => 'System',
			'sections' => array(
				'user_list' => array('title' => 'Users', 'route' => 'user_list', 'params' => array()),
			)
		),
		'logout' => array(
			'title' => 'Выход',
			'sections' => array(
                'logout' => array('title' => '', 'route' => 'logout'),
			),
		)
	),
);
