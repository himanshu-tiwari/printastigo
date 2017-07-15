<?php

namespace Printastigo\Controllers;

use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SubCategoryController{
	public function index($subCategory, Request $request, Response $response, Twig $view, Product $product){
		$products = $product->where('sub-category', $subCategory)->get();
		$classifications = $product->where('sub-category', $subCategory)->whereNotNull('classification')->select('classification')->groupBy('classification')->get();

		if (!$products) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $view->render($response, 'products/subCategory.twig',[
			"products" => $products,
			"classifications" => $classifications,
		]);
	}
}

?>