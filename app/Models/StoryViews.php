<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryViews extends Model
{
    protected $fillable = [
		'user_id',
		'media_stories_id'
	  ];

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

    public function stories()
	{
		return $this->belongsTo(Stories::class)->first();
	}
}
