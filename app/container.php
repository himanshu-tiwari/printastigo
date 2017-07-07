<?php

use Slim\Views\Twig;
use Printastigo\Basket\Basket;
use Printastigo\Models\Product;
use Slim\Views\TwigExtension;
use Interop\Container\ContainerInterface;
use Printastigo\Support\Storage\SessionStorage;
use Printastigo\Support\Storage\Contracts\StorageInterface;
use Printastigo\Validation\Contracts\ValidatorInterface;
use Printastigo\Validation\Validator;


return [
	'router' => DI\object(Slim\Router::class),
	ValidatorInterface::class => function(ContainerInterface $c){
		return new Validator;
	},
	StorageInterface::class => function(ContainerInterface $c){
		return new SessionStorage('cart');
	},
	Twig::class => function(ContainerInterface $c){
		$twig = new Twig(__DIR__.'/../resources/views',[
			'cache' => false
		]);

		$twig->addExtension(new TwigExtension(
			$c->get('router'),
			$c->get('request')->getUri()
		));

		$twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

		return $twig;
	},
	Product::class =>function (ContainerInterface $c){
		return new Product;
	},
	Basket::class =>function (ContainerInterface $c){
		return new Basket(
			$c->get(SessionStorage::class),
			$c->get(Product::class)
		);
	},
];

?>