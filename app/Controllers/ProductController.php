<?php

namespace Printastigo\Controllers;

use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController{
	public function get($slug, Request $request, Response $response, Twig $view, Product $product){
		$product = $product->where('slug', $slug)->first();
		
		if (!$product) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $view->render($response, 'products/product.twig',[
			"product" => $product,
		]);
	}
}

?>