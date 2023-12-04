<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }

    public function products()
    {
      return $this->belongsTo(Products::class)->first();
    }

    public function transactions()
    {
      return $this->belongsTo(Transactions::class)->first();
    }
}
