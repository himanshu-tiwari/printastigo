<?php

namespace Printastigo\Handlers;

use Printastigo\Handlers\Contracts\HandlerInterface;

class RecordFailedPayment implements HandlerInterface
{
	public function handle($event){
		$event->order->payment()->create([
			'failed' => true
		]);
	}
}

?>