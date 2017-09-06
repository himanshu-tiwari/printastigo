<?php

namespace Printastigo\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomUploadController{
	public function update(Request $request, Response $response, Twig $view, Product $product, Router $router){
		$date = date_create();
		$title = $request->getParam('specification') ? $request->getParam('specification') : ($request->getParam('classification') ? $request->getParam('classification') : ($request->getParam('sub-category') ? $request->getParam('sub-category') : $request->getParam('category'))) . ' ' . substr(date_format($date, 'U = d-m-Y H:i:s'),-19);

		$slug = implode("-", explode(" ", $title));

		$product = $product->firstOrCreate([
			'category' => $request->getParam('category'),
			'sub-category' => $request->getParam('sub-category'),
			'classification' => $request->getParam('classification'),
			'specification' => $request->getParam('specification'),
			'title' => $title,
			'slug' => $slug,
			'price' => $request->getParam('price'),
			'image' => $request->getParam('design'),
			'stock' => 10,
			'custom' => 1
		]);

		if (!$product) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $response->withRedirect($router->pathFor('cart.add', ['slug'=> $slug, 'quantity'=> 1]));
	}
}

?>