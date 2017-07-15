<?php

namespace Printastigo\Controllers;

use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SpecificationController{
	public function index($specification, Request $request, Response $response, Twig $view, Product $product){
		$products = $product->where('specification', $specification)->get();
		
		if (!$products) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $view->render($response, 'products/specification.twig', [
			'products' => $products,
		]);
	}
}

?>