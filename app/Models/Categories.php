<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	public function users()
	{
		return $this->hasMany(User::class)->where('status','active');
	}
}
