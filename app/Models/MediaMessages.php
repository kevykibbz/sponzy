<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMessages extends Model
{
  protected $fillable = [
    'messages_id',
    'type',
    'file',
    'width',
    'height',
    'video_poster',
    'duration_video',
    'quality_video',
    'file_name',
    'file_size',
    'token',
    'encoded',
    'job_id',
    'status',
    'created_at'
  ];

  public function messages()
  {
    return $this->belongsTo(Messages::class);
  }
}
