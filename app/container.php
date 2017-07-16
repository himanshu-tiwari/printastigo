<?php

use Slim\Views\Twig;
use Printastigo\Basket\Basket;
use Printastigo\Models\Product;
use Printastigo\Models\Order;
use Printastigo\Models\Customer;
use Printastigo\Models\Address;
use Printastigo\Models\Payment;
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
		$twig->getEnvironment()->addGlobal('allProducts', $c->get(Product::class));

		return $twig;
	},
	Product::class =>function (ContainerInterface $c){
		return new Product;
	},
	Order::class =>function (ContainerInterface $c){
		return new Order;
	},
	Customer::class =>function (ContainerInterface $c){
		return new Customer;
	},
	Address::class =>function (ContainerInterface $c){
		return new Address;
	},
	Payment::class =>function (ContainerInterface $c){
		return new Payment;
	},
	Basket::class =>function (ContainerInterface $c){
		return new Basket(
			$c->get(SessionStorage::class),
			$c->get(Product::class)
		);
	},
];

?>