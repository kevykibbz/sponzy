<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentsLikes extends Model {

	protected $guarded = array();

	public function user()
	{
		return $this->belongsTo('App\Models\User')->first();
	}


}
