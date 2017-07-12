<?php

namespace Printastigo\Events;

use Printastigo\Models\Order;
use Printastigo\Basket\Basket;

class OrderWasCreated extends Event
{
	public $order;
	public $basket;

	public function __construct(Order $order, Basket $basket){
		$this->order = $order;
		$this->basket = $basket;
	}
}

?>