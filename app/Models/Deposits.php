<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
	protected $guarded = [];
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

}
