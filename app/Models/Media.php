<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
  protected $fillable = [
    'updates_id',
    'user_id',
    'type',
    'image',
    'width',
    'height',
    'video',
    'video_poster',
    'video_embed',
    'music',
    'file',
    'file_name',
    'file_size',
    'img_type',
    'token',
    'status',
    'created_at'
  ];

  public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

  public function updates() {
        return $this->belongsTo('App\Models\Updates');
    }

}
