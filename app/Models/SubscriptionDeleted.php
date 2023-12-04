<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionDeleted extends Model
{
    protected $fillable = ['creator_id', 'user_id'];
}
