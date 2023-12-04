<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserDevices extends Model
{
    public $fillable = [
        'user_id',
        'player_id',
        'device_type'
    ];
}
