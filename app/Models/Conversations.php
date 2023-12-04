<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
  protected $guarded = [];
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo(User::class)->first();
  }

  public function last()
  {
    return $this->hasMany(Messages::class, 'conversations_id')
      ->where('messages.mode', 'active')
      ->orderBy('messages.updated_at', 'DESC')
      ->take(1)
      ->first();
  }

  public function messages()
  {
    return $this->hasMany(Messages::class, 'conversations_id')
      ->where('messages.mode', 'active')
      ->orderBy('messages.updated_at', 'DESC');
  }

  public function from()
  {
    return $this->belongsTo(User::class, 'from_user_id');
  }

  public function to()
  {
    return $this->belongsTo(User::class, 'to_user_id');
  }
}
