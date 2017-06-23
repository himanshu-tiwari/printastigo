<?php

$app->get('/', ['Printastigo\Controllers\HomeController', 'index'])->setName('home');
$app->get('/products/{slug}', ['Printastigo\Controllers\ProductController', 'get'])->setName('product.get');
$app->get('/cart', ['Printastigo\Controllers\CartController', 'index'])->setName('cart.index');
$app->get('/cart/add/{slug}/{quantity}', ['Printastigo\Controllers\CartController', 'add'])->setName('cart.add');

?>