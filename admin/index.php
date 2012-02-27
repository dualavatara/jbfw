<?php
require_once __DIR__ . '/lib/Admin/Application.php';

$config = require dirname(__FILE__) . '/config/config.php';
$app = new \Admin\Application($config);

$app->register(new \Admin\Extension\Session\SessionExtension());
$app->register(new \Admin\Extension\Template\TemplateExtension());
$app->register(new \Admin\Extension\Database\DatabaseExtension());
$app->register(new \Admin\Extension\Security\SecurityExtension());

$app->run();
