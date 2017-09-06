<?php

namespace Printastigo\Models;

use Illuminate\Database\Eloquent\Model;
use Printastigo\Models\Order;

class Product extends Model
{
	protected $fillable = [
		'category',
		'sub-category',
		'classification',
		'specification',
		'title',
		'slug',
		'description',
		'price',
		'image',
		'stock',
		'custom',
	];

	public $quantity = null;
	
	public function hasLowStock(){
		if ($this->outOfStock()) {
			return false;
		}

		return (bool) ($this->stock <=5);
	}

	public function outOfStock(){
		return $this->stock === 0;
	}

	public function inStock(){
		return $this->stock >=1;	
	}

	public function hasStock($quantity){
		return $this->stock >= $quantity;
	}

	public function order(){
		return $this->belongsToMany(Order::class, 'orders_products')->withPivot('quantity');
	}
}

?>