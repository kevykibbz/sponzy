<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRates extends Model
{
    use HasFactory;

    public function country()
  	{
  		return $this->belongsTo(Countries::class, 'country', 'country_code')->first();
  	}

    public function state()
  	{
  		return $this->belongsTo(States::class, 'iso_state', 'code')->first();
  	}
}
