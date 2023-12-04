<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaWelcomeMessage extends Model
{
  protected $fillable = [
    'creator_id',
    'type',
    'file',
    'width',
    'height',
    'video_poster',
    'file_name',
    'file_size',
    'file_size_bytes',
    'mime_type',
    'token',
    'status',
    'created_at'
  ];

  public function creator()
  {
    return $this->belongsTo(User::class);
  }
}
