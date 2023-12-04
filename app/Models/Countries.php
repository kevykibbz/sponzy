<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model 
{
	protected $guarded = [];
	public $timestamps = false;

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function states()
	{
		return $this->hasMany(States::class);
	}

}
