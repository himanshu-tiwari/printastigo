<?php

$app->get('/', ['Printastigo\Controllers\HomeController', 'index'])->setName('home');
$app->get('/products/{slug}', ['Printastigo\Controllers\ProductController', 'get'])->setName('product.get');

?>