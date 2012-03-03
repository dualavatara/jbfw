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

		'article_edit' => array('/article/edit/{id}',       'Article', 'edit'),
		'article_list' => array('/article/list',            'Article', 'list'),
		'article_add' => array('/article/add',              'Article', 'add'),
		'article_delete' => array('/article/delete/{id}',   'Article', 'delete'),
		'article_save' => array('/article/save',            'Article', 'save'),

		'discount_edit' => array('/discount/edit/{id}',       'Discount', 'edit'),
		'discount_list' => array('/discount/list',            'Discount', 'list'),
		'discount_add' => array('/discount/add',              'Discount', 'add'),
		'discount_delete' => array('/discount/delete/{id}',   'Discount', 'delete'),
		'discount_save' => array('/discount/save',            'Discount', 'save'),

		'user_edit'    => array('/user/edit/{id}',      'User', 'edit'),
		'user_delete'  => array('/user/delete/{id}',    'User', 'delete'),
		'user_list'    => array('/user/list',           'User', 'list'),
		'user_save'    => array('/user/save',           'User', 'save'),
		'user_add'     => array('/user/add',            'User', 'add'),
	),
	
	'menu' => array(
		'modules' => array(
			'title' => 'Объекты',
			'sections' => array(
				'currency_list' => array('title' => 'Валюты',     'route' => 'currency_list',   'params' => array()),
				'price_list' => array('title' => 'Цены', 'route' => 'price_list', 'params' => array()),
				'article_list' => array('title' => 'Статьи', 'route' => 'article_list', 'params' => array()),
				'discount_list' => array('title' => 'Скидки', 'route' => 'discount_list', 'params' => array()),
			)
		),
		'sys' => array(
			'title' => 'Системные',
			'sections' => array(
				'user_list' => array('title' => 'Пользователи', 'route' => 'user_list', 'params' => array()),
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
