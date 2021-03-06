<?php

$app->get('/', ['Printastigo\Controllers\HomeController', 'index'])->setName('home');
$app->get('/category/{category}', ['Printastigo\Controllers\CategoryController', 'index'])->setName('category.get');
$app->get('/subCategory/{subCategory}', ['Printastigo\Controllers\SubCategoryController', 'index'])->setName('subCategory.get');
$app->get('/classification/{classification}', ['Printastigo\Controllers\ClassificationController', 'index'])->setName('classification.get');
$app->get('/specification/{specification}', ['Printastigo\Controllers\SpecificationController', 'index'])->setName('specification.get');

$app->get('/products/{slug}', ['Printastigo\Controllers\ProductController', 'get'])->setName('product.get');

$app->get('/cart', ['Printastigo\Controllers\CartController', 'index'])->setName('cart.index');
$app->get('/cart/add/{slug}/{quantity}', ['Printastigo\Controllers\CartController', 'add'])->setName('cart.add');
$app->post('/cart/update/{slug}', ['Printastigo\Controllers\CartController', 'update'])->setName('cart.update');

$app->get('/order', ['Printastigo\Controllers\OrderController', 'index'])->setName('order.index');
$app->get('/order/{hash}', ['Printastigo\Controllers\OrderController', 'show'])->setName('order.show');
$app->post('/order', ['Printastigo\Controllers\OrderController', 'create'])->setName('order.create');

$app->get('/braintree/token', ['Printastigo\Controllers\BraintreeController', 'token'])->setName('braintree.token');

$app->post('/upload', ['Printastigo\Controllers\CustomUploadController', 'update'])->setName('upload');

$app->get('/customize/{slug}', ['Printastigo\Controllers\CustomizeDesignController', 'get'])->setName('customize.get');
?>