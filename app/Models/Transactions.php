<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['approved'];

    public function user()
    {
      return $this->belongsTo(User::class)->first();
    }

		public function subscribed()
    {
      return $this->belongsTo(User::class, 'subscribed')->first();
    }

    public function subscription()
    {
      return $this->belongsTo(Subscriptions::class, 'subscriptions_id')->first();
    }
}
