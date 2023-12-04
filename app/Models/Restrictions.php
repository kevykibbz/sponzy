<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restrictions extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'user_restricted'
    ];

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }

    public function userRestricted()
    {
      return $this->belongsTo(User::class, 'user_restricted')->first();
    }
}
