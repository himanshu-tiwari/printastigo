<?php

use Printastigo\App;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Views\Twig;

session_start();

require __DIR__."/../vendor/autoload.php";

$app = new App;

$container = $app->getContainer();

$capsule = new Capsule;

$capsule->addConnection([
	'driver' => 'mysql',
	'host' => '127.0.0.1',
	'database' => 'printastigo',
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

require __DIR__."/../app/routes.php";

$app->add(new \Printastigo\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \Printastigo\Middleware\OldInputMiddleware($container->get(Twig::class)));


?>