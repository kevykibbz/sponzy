<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSessions extends Model
{
    public $fillable = [
        'user_id',
        'ip',
        'device',
        'device_type',
        'browser',
        'platform',
        'country',
        'updated_at'
    ];

    public function getNameBrowser()
    {
        $explode = explode(' ', $this->browser);
        return $explode[0];
    }

    public function getNamePlatform()
    {
        $explode = explode(' ', $this->platform);
        return $explode[0];
    }
}
