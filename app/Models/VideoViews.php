<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoViews extends Model
{
    protected $guarded = [];

    public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function post()
	{
		return $this->belongsTo(Updates::class)->first();
	}
}
