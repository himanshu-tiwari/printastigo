<?php

namespace Printastigo\Handlers;

use Printastigo\Handlers\Contracts\HandlerInterface;

class EmptyBasket implements HandlerInterface
{
	public function handle($event){
		$event->basket->clear();
	}
}

?>