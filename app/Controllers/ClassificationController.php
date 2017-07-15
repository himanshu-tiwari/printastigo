<?php

namespace Printastigo\Controllers;

use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClassificationController{
	public function index($classification, Request $request, Response $response, Twig $view, Product $product){
		$products = $product->where('classification', $classification)->get();
		$specifications = $product->where('classification', $classification)->whereNotNull('specification')->select('specification')->groupBy('specification')->get();

		if (!$products) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $view->render($response, 'products/classification.twig',[
			"products" => $products,
			"specifications" => $specifications,
		]);
	}
}

?>