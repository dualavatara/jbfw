<?php

require_once 'config/config.php';

return array(

	/* Main parameters, all required */
	'baseUrl' => '/admin', 'rootPath' => '.', 'ctlPath' => 'ctl/', 'title' => 'Администрирование',

	'dataPath' => '../' . PATH_DATA,

	/* Extension's options start */
	'db.options' => array(
		//		'host' => DB_HOST,
		//		'port' => DB_PORT,
		'user' => DB_USER, 'pass' => DB_PASS, //		'name' => DB_NAME,
		'dsn' => DB_DSN, 'charset' => DB_CHARSET,
		//'class' => '\Admin\Extension\Database\Database',
	),

	'template.options' => array(
		'path' => 'tpl/'
	),

	'security.options' => array(
		'login_route' => 'login', 'session_key' => 'user', 'class' => 'SecurityUser', 'logout_route' => 'logout',
	), /* End */

	'routes' => array(
		'home' => array('/', 'User', 'list'),
		'closed_index' => array('/', 'Site', 'closed_index'),

		'login' => array('/login', 'Auth', 'login'), 'logout' => array('/logout', 'Auth', 'logout'),

		'static' => array('/s/{key}', 'Data', 'get'),

		'currency_edit' => array('/currency/edit/{id}', 'Currency', 'edit'),
		'currency_list' => array('/currency/list', 'Currency', 'list'),
		'currency_add' => array('/currency/add', 'Currency', 'add'),
		'currency_delete' => array('/currency/delete/{id}', 'Currency', 'delete'),
		'currency_save' => array('/currency/save', 'Currency', 'save'),
		'currency_json' => array('/currency/json/{name}', 'Currency', 'json'),

		'price_edit' => array('/price/edit/{id}', 'Price', 'edit'),
		'price_list' => array('/price/list', 'Price', 'list'),
		'price_add' => array('/price/add', 'Price', 'add'),
		'price_delete' => array('/price/delete/{id}', 'Price', 'delete'),
		'price_save' => array('/price/save', 'Price', 'save'),

		'article_edit' => array('/article/edit/{id}', 'Article', 'edit'),
		'article_list' => array('/article/list', 'Article', 'list'),
		'article_add' => array('/article/add', 'Article', 'add'),
		'article_delete' => array('/article/delete/{id}', 'Article', 'delete'),
		'article_save' => array('/article/save', 'Article', 'save'),

		'discount_edit' => array('/discount/edit/{id}', 'Discount', 'edit'),
		'discount_list' => array('/discount/list', 'Discount', 'list'),
		'discount_add' => array('/discount/add', 'Discount', 'add'),
		'discount_delete' => array('/discount/delete/{id}', 'Discount', 'delete'),
		'discount_save' => array('/discount/save', 'Discount', 'save'),

		'user_edit' => array('/user/edit/{id}', 'User', 'edit'),
		'user_delete' => array('/user/delete/{id}', 'User', 'delete'),
		'user_list' => array('/user/list', 'User', 'list'),
		'user_save' => array('/user/save', 'User', 'save'),
		'user_add' => array('/user/add', 'User', 'add'),

		'beach_edit' => array('/beach/edit/{id}', 'Beach', 'edit'),
		'beach_list' => array('/beach/list', 'Beach', 'list'),
		'beach_add' => array('/beach/add', 'Beach', 'add'),
		'beach_delete' => array('/beach/delete/{id}', 'Beach', 'delete'),
		'beach_save' => array('/beach/save', 'Beach', 'save'),

		'setting_edit' => array('/setting/edit/{id}', 'Setting', 'edit'),
		'setting_list' => array('/setting/list', 'Setting', 'list'),
		'setting_add' => array('/setting/add', 'Setting', 'add'),
		'setting_delete' => array('/setting/delete/{id}', 'Setting', 'delete'),
		'setting_save' => array('/setting/save', 'Setting', 'save'),

		'resort_edit' => array('/resort/edit/{id}', 'Resort', 'edit'),
		'resort_list' => array('/resort/list', 'Resort', 'list'),
		'resort_add' => array('/resort/add', 'Resort', 'add'),
		'resort_delete' => array('/resort/delete/{id}', 'Resort', 'delete'),
		'resort_save' => array('/resort/save', 'Resort', 'save'),
		'resort_json' => array('/resort/json/{name}', 'Resort', 'json'),

		'customer_edit' => array('/customer/edit/{id}', 'Customer', 'edit'),
		'customer_list' => array('/customer/list', 'Customer', 'list'),
		'customer_json' => array('/customer/json/{name}', 'Customer', 'json'),
		'customer_add' => array('/customer/add', 'Customer', 'add'),
		'customer_delete' => array('/customer/delete/{id}', 'Customer', 'delete'),
		'customer_save' => array('/customer/save', 'Customer', 'save'),

		'carrentoffice_edit' => array('/carrentoffice/edit/{id}', 'CarRentOffice', 'edit'),
		'carrentoffice_list' => array('/carrentoffice/list', 'CarRentOffice', 'list'),
		'carrentoffice_add' => array('/carrentoffice/add', 'CarRentOffice', 'add'),
		'carrentoffice_delete' => array('/carrentoffice/delete/{id}', 'CarRentOffice', 'delete'),
		'carrentoffice_save' => array('/carrentoffice/save', 'CarRentOffice', 'save'),
		'carrentoffice_json' => array('/carrentoffice/json/{name}', 'CarRentOffice', 'json'),

		'banner_edit' => array('/banner/edit/{id}', 'Banner', 'edit'),
		'banner_list' => array('/banner/list', 'Banner', 'list'),
		'banner_add' => array('/banner/add', 'Banner', 'add'),
		'banner_delete' => array('/banner/delete/{id}', 'Banner', 'delete'),
		'banner_save' => array('/banner/save', 'Banner', 'save'),

		'realty_edit' => array('/realty/edit/{id}', 'Realty', 'edit'),
		'realty_list' => array('/realty/list', 'Realty', 'list'),
		'realty_add' => array('/realty/add', 'Realty', 'add'),
		'realty_delete' => array('/realty/delete/{id}', 'Realty', 'delete'),
		'realty_save' => array('/realty/save', 'Realty', 'save'),

		'realtyimage_edit' => array('/realtyimage/edit/{id}', 'RealtyImage', 'edit'),
		'realtyimage_list' => array('/realtyimage/list', 'RealtyImage', 'list'),
		'realtyimage_add' => array('/realtyimage/add', 'RealtyImage', 'add'),
		'realtyimage_delete' => array('/realtyimage/delete/{id}', 'RealtyImage', 'delete'),
		'realtyimage_save' => array('/realtyimage/save', 'RealtyImage', 'save'),

		'appartment_edit' => array('/appartment/edit/{id}', 'Appartment', 'edit'),
		'appartment_list' => array('/appartment/list', 'Appartment', 'list'),
		'appartment_add' => array('/appartment/add', 'Appartment', 'add'),
		'appartment_delete' => array('/appartment/delete/{id}', 'Appartment', 'delete'),
		'appartment_save' => array('/appartment/save', 'Appartment', 'save'),

		'cartype_edit' => array('/cartype/edit/{id}', 'CarType', 'edit'),
		'cartype_list' => array('/cartype/list', 'CarType', 'list'),
		'cartype_add' => array('/cartype/add', 'CarType', 'add'),
		'cartype_delete' => array('/cartype/delete/{id}', 'CarType', 'delete'),
		'cartype_save' => array('/cartype/save', 'CarType', 'save'),
		'cartype_json' => array('/cartype/json/{name}', 'CarType', 'json'),

		'carimage_edit' => array('/carimage/edit/{id}',       'CarImage', 'edit'),
		'carimage_list' => array('/carimage/list',            'CarImage', 'list'),
		'carimage_add' => array('/carimage/add',              'CarImage', 'add'),
		'carimage_delete' => array('/carimage/delete/{id}',   'CarImage', 'delete'),
		'carimage_save' => array('/carimage/save',            'CarImage', 'save'),

		'car_edit' => array('/car/edit/{id}',       'Car', 'edit'),
		'car_list' => array('/car/list',            'Car', 'list'),
		'car_add' => array('/car/add',              'Car', 'add'),
		'car_delete' => array('/car/delete/{id}',   'Car', 'delete'),
		'car_save' => array('/car/save',            'Car', 'save'),

		'navigation_edit' => array('/navigation/edit/{id}',       'Navigation', 'edit'),
		'navigation_list' => array('/navigation/list',            'Navigation', 'list'),
		'navigation_add' => array('/navigation/add',              'Navigation', 'add'),
		'navigation_delete' => array('/navigation/delete/{id}',   'Navigation', 'delete'),
		'navigation_save' => array('/navigation/save',            'Navigation', 'save'),

		'articleimage_edit' => array('/articleimage/edit/{id}',       'ArticleImage', 'edit'),
		'articleimage_list' => array('/articleimage/list',            'ArticleImage', 'list'),
		'articleimage_add' => array('/articleimage/add',              'ArticleImage', 'add'),
		'articleimage_delete' => array('/articleimage/delete/{id}',   'ArticleImage', 'delete'),
		'articleimage_save' => array('/articleimage/save',            'ArticleImage', 'save'),

		'realtytype_edit' => array('/realtytype/edit/{id}',       'RealtyType', 'edit'),
		'realtytype_list' => array('/realtytype/list',            'RealtyType', 'list'),
		'realtytype_add' => array('/realtytype/add',              'RealtyType', 'add'),
		'realtytype_delete' => array('/realtytype/delete/{id}',   'RealtyType', 'delete'),
		'realtytype_save' => array('/realtytype/save',            'RealtyType', 'save'),

		'appartmenttype_edit' => array('/appartmenttype/edit/{id}',       'AppartmentType', 'edit'),
		'appartmenttype_list' => array('/appartmenttype/list',            'AppartmentType', 'list'),
		'appartmenttype_add' => array('/appartmenttype/add',              'AppartmentType', 'add'),
		'appartmenttype_delete' => array('/appartmenttype/delete/{id}',   'AppartmentType', 'delete'),
		'appartmenttype_save' => array('/appartmenttype/save',            'AppartmentType', 'save'),

		'place_edit' => array('/place/edit/{id}',       'Place', 'edit'),
		'place_list' => array('/place/list',            'Place', 'list'),
		'place_add' => array('/place/add',              'Place', 'add'),
		'place_delete' => array('/place/delete/{id}',   'Place', 'delete'),
		'place_save' => array('/place/save',            'Place', 'save'),
		'place_json' => array('/place/json/{name}', 'Place', 'json'),

		'urlaliases_edit' => array('/urlaliases/edit/{id}',       'UrlAliases', 'edit'),
		'urlaliases_list' => array('/urlaliases/list',            'UrlAliases', 'list'),
		'urlaliases_add' => array('/urlaliases/add',              'UrlAliases', 'add'),
		'urlaliases_delete' => array('/urlaliases/delete/{id}',   'UrlAliases', 'delete'),
		'urlaliases_save' => array('/urlaliases/save',            'UrlAliases', 'save'),


	),

	'menu' => array(
		'realty' => array(
			'title' => 'Недвижимость', 'sections' => array(
				'RealtyType' => array('title' => 'Типы объектов недвижимости',     'route' => 'realtytype_list',   'params' => array()),
				'AppartmentType' => array('title' => 'Типы аппартаментов',     'route' => 'appartmenttype_list',   'params' => array()),
				'Beach' => array('title' => 'Пляжи', 'route' => 'beach_list', 'params' => array()),
				'Resort' => array('title' => 'Курорты', 'route' => 'resort_list', 'params' => array()),
				'Realty' => array(
					'title' => 'Объекты недвижимости', 'route' => 'realty_list', 'params' => array()
				),
			)
		),
		'cars' => array(
			'title' => 'Автомобили', 'sections' => array(
				'CarRentOffice' => array(
					'title' => 'Конторы по прокату авто', 'route' => 'carrentoffice_list', 'params' => array()
				),
				'CarType' => array('title' => 'Типы автомобилей',     'route' => 'cartype_list',   'params' => array()),
				'Car' => array('title' => 'Автомобили',     'route' => 'car_list',   'params' => array()),

			)
		),
		'misc' => array(
			'title' => 'Разное', 'sections' => array(
				'Banner' => array('title' => 'Баннеры', 'route' => 'banner_list', 'params' => array()),
				'Currency' => array('title' => 'Валюты', 'route' => 'currency_list', 'params' => array()),
				'Article' => array('title' => 'Статьи', 'route' => 'article_list', 'params' => array()),
				//'discount_list' => array('title' => 'Скидки', 'route' => 'discount_list', 'params' => array()),
				'Customer' => array('title' => 'Клиенты', 'route' => 'customer_list', 'params' => array()),
				'Navigation' => array('title' => 'Навигация',     'route' => 'navigation_list',   'params' => array('parent_field' => 'parent_id')),
				'UrlAliases' => array('title' => 'Алиасы URL',     'route' => 'urlaliases_list',   'params' => array()),
			)
		),
		/*'modules' => array(
			'title' => 'Объекты', 'sections' => array(
				'currency_list' => array('title' => 'Валюты', 'route' => 'currency_list', 'params' => array()),
				'article_list' => array('title' => 'Статьи', 'route' => 'article_list', 'params' => array()),
				'discount_list' => array('title' => 'Скидки', 'route' => 'discount_list', 'params' => array()),
				'beach_list' => array('title' => 'Пляж', 'route' => 'beach_list', 'params' => array()),
				'resort_list' => array('title' => 'Курорты', 'route' => 'resort_list', 'params' => array()),
				'customer_list' => array('title' => 'Клиент', 'route' => 'customer_list', 'params' => array()),
				'carrentoffice_list' => array(
					'title' => 'Конторы по прокату авто', 'route' => 'carrentoffice_list', 'params' => array()
				),
				'realty_list' => array(
					'title' => 'Объекты недвижимости', 'route' => 'realty_list', 'params' => array()
				),
				'cartype_list' => array('title' => 'Название объекта',     'route' => 'cartype_list',   'params' => array()),
			)
		), */
		'sys' => array(
			'title' => 'Системные', 'sections' => array(
				'User' => array('title' => 'Пользователи', 'route' => 'user_list', 'params' => array()),
				'Settings' => array('title' => 'Настройки сайта', 'route' => 'setting_list', 'params' => array()),
			)
		),
		'logout' => array(
			'title' => 'Выход', 'sections' => array(
				'Auth' => array('title' => '', 'route' => 'logout'),
			),
		)
	),
);
