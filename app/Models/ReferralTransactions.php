<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralTransactions extends Model
{
  protected $fillable = [
    'transactions_id',
    'referrals_id',
    'user_id',
    'referred_by',
    'earnings',
    'type',
    'created_at'
  ];

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }

		public function referredBy()
    {
      return $this->belongsTo(User::class, 'referred_by')->first();
    }

}
