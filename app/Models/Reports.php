<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{

  protected $guarded = [];
  const UPDATED_AT = null;

  public function user()
  {
    return $this->belongsTo(User::class)->first();
  }

  public function userReported()
  {
    return $this->belongsTo(User::class, 'report_id')->first();
  }

  public function updates()
  {
    return $this->belongsTo(Updates::class, 'report_id')->first();
  }

  public function products()
  {
    return $this->belongsTo(Products::class, 'report_id')->first();
  }

  public function live()
  {
    return $this->belongsTo(User::class, 'report_id')->first();
  }
}
