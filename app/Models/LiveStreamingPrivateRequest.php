<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LiveStreamingPrivateStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveStreamingPrivateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'creator_id',
        'minutes',
        'status',
      ];

      protected $with = [
        'user:id,name,username,wallet,language',
        'creator:id,name,username,balance',
        'transaction:id,amount,taxes,earning_net_user',
      ];

    protected $casts = [
        'status' => LiveStreamingPrivateStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactions::class);
    }
}
