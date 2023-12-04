<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'name',
      'price',
      'interval',
      'paystack',
      'status',
      'created_at'
    ];

    public function user()
    {
  		return $this->belongsTo(User::class)->first();
  	}
}
