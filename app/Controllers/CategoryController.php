<?php

namespace Printastigo\Controllers;

use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryController{
	public function index($category, Request $request, Response $response, Twig $view, Product $product){
		$products = $product->where('category', $category)->get();
		$subCategories = $product->where('category', $category)->select('sub-category')->whereNotNull('sub-category')->groupBy('sub-category')->get();

		if (!$products) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $view->render($response, 'products/category.twig',[
			"products" => $products,
			"subCategories" => $subCategories,
		]);
	}
}

?>