<?php

namespace Printastigo\Controllers;

use Slim\Router;
use Slim\Views\Twig;

use Printastigo\Basket\Basket;

use Printastigo\Models\Product;
use Printastigo\Models\Customer;
use Printastigo\Models\Address;
use Printastigo\Models\Order;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Printastigo\Validation\Contracts\ValidatorInterface;
use Printastigo\Validation\Forms\OrderForm;

use Braintree_Transaction;

use Printastigo\Events\OrderWasCreated;

use Printastigo\Handlers\EmptyBasket;
use Printastigo\Handlers\MarkOrderPaid;
use Printastigo\Handlers\RecordFailedPayment;
use Printastigo\Handlers\RecordSuccessfulPayment;
use Printastigo\Handlers\UpdateStock;

class OrderController
{
	protected $basket;
	protected $router;
	protected $validator;

	public function __construct(Basket $basket, Router $router, ValidatorInterface $validator){
		$this->basket = $basket;
		$this->router = $router;
		$this->validator = $validator;
	}

	public function index(Request $request, Response $response, Twig $view)
	{
		$this->basket->refresh();

		if (!$this->basket->subTotal()) {
			return $response->withRedirect($this->router->pathFor('cart.index'));
		}

		return $view->render($response, 'order/index.twig');
	}

	public function show(Request $request, Response $response, Twig $view, Order $order, $hash)
	{
		$order = $order->with(['address','products'])->where('hash',$hash)->first();

		if (!$order) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		return $view->render($response, 'order/show.twig', [
			'order' => $order,
		]);
	}

	public function create(Request $request, Response $response, Customer $customer, Address $address)
	{
		$this->basket->refresh();

		if (!$this->basket->subTotal()) {
			return $response->withRedirect($this->router->pathFor('cart.index'));
		}

		if (!$request->getParam('payment_method_nonce')) {
			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		$validation = $this->validator->validate($request, OrderForm::rules());

		if($validation->fails()){
			return $response->withRedirect($this->router->pathFor('order.index'));	
		}

		$hash = bin2hex(random_bytes(32));

		$customer = $customer->firstOrCreate([
			'name' => $request->getParam('name'),
			'email' => $request->getParam('email'),
		]);

		$address = $address->firstOrCreate([
			'address1' => $request->getParam('address1'),
			'address2' => $request->getParam('address2'),
			'city' => $request->getParam('city'),
			'postal_code' => $request->getParam('postal_code'),
		]);

		$order = $customer->orders()->create([
			'hash' => $hash,
			'paid' => false,
			'total' => $this->basket->subTotal() + 5,
			'address_id' => $address->id,
		]);

		$allItems = $this->basket->all(); 

		$order->products()->saveMany(
			$allItems,
			$this->getQuantities($allItems)
		);

		$result = Braintree_Transaction::sale([
		  'amount' => $this->basket->subTotal() + 5,
		  'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
		  'options' => [
		    'submitForSettlement' => true
		  ]
		]);

		$event = new OrderWasCreated($order, $this->basket);

		if (!$result->success) {
			$event->attach(new RecordFailedPayment);			

			$event->dispatch();
			
			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		$event->attach([
			new UpdateStock,
			new MarkOrderPaid,
			new RecordSuccessfulPayment($result->transaction->id),
			new EmptyBasket,
		]);

		$event->dispatch();

		return $response->withRedirect($this->router->pathFor('order.show', [
			'hash' => $hash,
		]));
	}

	protected function getQuantities($items){
		$quantities = [];

		foreach ($items as $item){
			$quantities[] = ['quantity' => $item->quantity];
		}

		return $quantities;
	}
}

?>