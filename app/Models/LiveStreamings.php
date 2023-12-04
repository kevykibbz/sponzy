<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveStreamings extends Model
{
    use HasFactory;

    protected $fillable = [
      'type',
      'user_id',
      'buyer_id',
      'name',
      'channel',
      'minutes',
      'price',
      'status',
      'joined_at',
      'availability',
      'token',
    ];

    public function user()
  	{
  		return $this->belongsTo(User::class)->first();
  	}

    public function comments()
    {
      return $this->hasMany(LiveComments::class);
    }

    public function likes()
    {
      return $this->hasMany(LiveLikes::class);
    }

    public function onlineUsers()
    {
      return $this->hasMany(LiveOnlineUsers::class)
        ->where('updated_at', '>', now()->subSeconds(10));
    }

    public function getTimeElapsedAttribute()
    {
      return $this->updated_at->diffInMinutes($this->created_at);
    }

    public function getTimeElapsedLivePrivateAttribute()
    {
      return $this->updated_at->diffInMinutes($this->joined_at);
    }
}
