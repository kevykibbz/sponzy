<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaStories extends Model
{
    protected $fillable = [
        'stories_id',
        'name',
        'type',
        'video_length',
        'video_poster',
        'text',
        'font_color',
        'font',
        'status',
        'created_at'
      ];

    public function stories() 
    {
        return $this->belongsTo(Stories::class);
    }

    public function views()
	{
		return $this->hasMany(StoryViews::class);
	}
}
