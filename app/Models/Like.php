<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function updates() {
        return $this->hasMany('App\Models\Updates');
    }

}
