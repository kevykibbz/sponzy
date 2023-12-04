<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveLikes extends Model
{
  protected $guarded = array();
  protected $fillable = [
    'user_id',
    'live_streamings_id'
  ];
  
    use HasFactory;

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }
}
