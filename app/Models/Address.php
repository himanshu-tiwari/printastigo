<?php

namespace Printastigo\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'address1',
		'address2',
		'city',
		'postal_code',
	];
}

?>