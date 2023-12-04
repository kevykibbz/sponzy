<?php

namespace App\Models;

use App\Enums\AdvertisingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertising extends Model
{
  use HasFactory;

  protected $guarded = [];

  protected $casts = [
    'status' => AdvertisingStatus::class
  ];

  public function impressions(): void
  {
    $impression = AdClickImpression::firstOrNew([
      'advertisings_id' => $this->id,
      'type' => 'impression',
      'ip' => request()->ip()
    ]);

    if (!$impression->exists) {
      $this->increment('impressions');

      $impression->save();
    }
  }
}
