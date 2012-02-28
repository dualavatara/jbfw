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
		
		'currency_edit' => array('/currency/edit/{id}',       'Currency', 'edit'),
		'currency_list' => array('/currency/list',            'Currency', 'list'),
        'currency_add' => array('/currency/add',              'Currency', 'add'),
		'currency_delete' => array('/currency/delete/{id}',   'Currency', 'delete'),
		'currency_save' => array('/currency/save',            'Currency', 'save'),

		'price_edit' => array('/price/edit/{id}',       'Price', 'edit'),
		'price_list' => array('/price/list',            'Price', 'list'),
		'price_add' => array('/price/add',              'Price', 'add'),
		'price_delete' => array('/price/delete/{id}',   'Price', 'delete'),
		'price_save' => array('/price/save',            'Price', 'save'),

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
				'currency_list' => array('title' => 'Currencys',     'route' => 'currency_list',   'params' => array()),
				'price_list' => array('title' => 'Price', 'route' => 'price_list', 'params' => array()),
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
