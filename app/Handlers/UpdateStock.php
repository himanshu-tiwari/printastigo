<?php

namespace Printastigo\Handlers;

use Printastigo\Handlers\Contracts\HandlerInterface;

class UpdateStock implements HandlerInterface
{
	public function handle($event){
		foreach ($event->basket->all() as $product) {
			$product->decrement('stock', $product->quantity);
		}
	}
}

?>