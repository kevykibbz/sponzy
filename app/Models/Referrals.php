<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referrals extends Model
{
  protected $fillable = [
    'user_id',
    'referred_by'
  ];

  public function user()
  {
    return $this->belongsTo(User::class)->first();
  }

  public function referredBy()
  {
    return $this->belongsTo(User::class, 'referred_by')->first();
  }

  public function earnings()
  {
    return $this->hasMany(ReferralTransactions::class)->sum('earnings');
  }
}
