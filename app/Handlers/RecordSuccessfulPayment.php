<?php

namespace Printastigo\Handlers;

use Printastigo\Handlers\Contracts\HandlerInterface;

class RecordSuccessfulPayment implements HandlerInterface
{
	protected $transactionId;
	
	public function __construct($transactionId){
		$this->transactionId = $transactionId;
	}

	public function handle($event){
		$event->order->payment()->create([
			'failed' => false,
			'transaction_id' => $this->transactionId,
		]);
	}
}

?>