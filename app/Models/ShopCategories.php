<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategories extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	public function products()
	{
		return $this->hasMany(Products::class, 'category')->where('status','1');
	}
}
