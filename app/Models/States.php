<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function country()
  	{
  		return $this->belongsTo(Countries::class, 'countries_id')->first();
  	}
}
