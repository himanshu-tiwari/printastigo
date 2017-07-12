<?php

use Printastigo\App;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Views\Twig;
use Printastigo\Config\Mode;

use Psr\Http\Message\ResponseInterface as Response;

session_start();

require __DIR__."/../vendor/autoload.php";

$app = new App;

$container = $app->getContainer();

$mode = new Mode;
$env = $mode->getEnvDetails('development');

$capsule = new Capsule;

$capsule->addConnection([
	'driver' => $env['db']['driver'],
	'host' => $env['db']['host'],
	'database' => $env['db']['database'],
	'username' => $env['db']['username'],
	'password' => $env['db']['password'],
	'charset' => $env['db']['charset'],
	'collation' => $env['db']['collation'],
	'prefix' => $env['db']['prefix']
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

Braintree_Configuration::environment($env['braintree']['environment']);
Braintree_Configuration::merchantId($env['braintree']['merchantId']);
Braintree_Configuration::publicKey($env['braintree']['publicKey']);
Braintree_Configuration::privateKey($env['braintree']['privateKey']);

require __DIR__."/../app/routes.php";

$app->add(new \Printastigo\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \Printastigo\Middleware\OldInputMiddleware($container->get(Twig::class)));


?>