<?php

namespace Printastigo\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Printastigo\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Printastigo\Validation\Contracts\ValidatorInterface;
use Printastigo\Validation\Forms\CustomUploadForm;

class CustomUploadController{
	protected $router;
	protected $validator;

	public function __construct(Router $router, ValidatorInterface $validator){
		$this->router = $router;
		$this->validator = $validator;
	}

	public function update(Request $request, Response $response, Twig $view, Product $product){
		$date = date_create();
		
		if ($request->getParam('specification')) {
			$path = $request->getParam('specification');
			$type = 'specification';  
		}
		else{
			if ($request->getParam('classification')) {
				$path = $request->getParam('classification');
				$type = 'classification';
			}
			else{
				if ($request->getParam('sub-category')) {
					$path = $request->getParam('sub-category');
					$type = 'sub-category';
				}
				else{
					$path = $request->getParam('category');
					$type = 'category';		
				}
			}
		}	

		$title = $path.' '.substr(date_format($date, 'U = d-m-Y H:i:s'),-19);

		$slug = implode("-", explode(" ", $title));

		$validation = $this->validator->validate($request, CustomUploadForm::rules());

		if($validation->fails()){
			return $response->withRedirect($this->router->pathFor($type.'.get', [$type => $path]));	
		}

		$product = $product->firstOrCreate([
			'category' => $request->getParam('category'),
			'sub-category' => $request->getParam('sub-category'),
			'classification' => $request->getParam('classification'),
			'specification' => $request->getParam('specification'),
			'title' => $title,
			'description' => $request->getParam('description'),
			'slug' => $slug,
			'price' => $request->getParam('price'),
			'image' => $request->getParam('design'),
			'stock' => $request->getParam('amount'),
			'custom' => 1
		]);

		if (!$product) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		return $response->withRedirect($this->router->pathFor('cart.add', ['slug'=> $slug, 'quantity'=> 1]));
	}
}

?>