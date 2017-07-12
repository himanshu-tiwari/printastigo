<?php

namespace Printastigo\Handlers;

use Printastigo\Handlers\Contracts\HandlerInterface;

class MarkOrderPaid implements HandlerInterface
{
	public function handle($event){
		$event->order->update([
			'paid' => true,
		]);
	}
}

?>