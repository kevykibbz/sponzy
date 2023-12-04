<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Messages extends Model
{
  protected $guarded = [];

  public static function conversations() 
  {
    $fields = 'id,avatar,name,username,hide_name,verified_id,active_status_online';

    return self::from('messages as m1')
      ->select('m1.*')
      ->join(DB::raw(
          '(
          SELECT
              LEAST(from_user_id, to_user_id) AS from_user_id,
              GREATEST(from_user_id, to_user_id) AS to_user_id,
              MAX(id) AS max_id
          FROM messages
          GROUP BY
              LEAST(from_user_id, to_user_id),
              GREATEST(from_user_id, to_user_id)
      ) AS m2'
      ), fn($join) => $join
          ->on(DB::raw('LEAST(m1.from_user_id, m1.to_user_id)'), '=', 'm2.from_user_id')
          ->on(DB::raw('GREATEST(m1.from_user_id, m1.to_user_id)'), '=', 'm2.to_user_id')
          ->on('m1.id', '=', 'm2.max_id'))
      ->where('m1.from_user_id', auth()->id())
      ->orWhere('m1.to_user_id', auth()->id())
      ->orderByDesc('m1.created_at')
      ->orderByDesc('m1.id')
      ->with(['sender:'.$fields, 'receiver:'.$fields, 'media'])
      ->simplePaginate(10);
  }

  public function sender()
  {
    return $this->belongsTo(User::class, 'from_user_id');
  }

  public function receiver()
  {
    return $this->belongsTo(User::class, 'to_user_id');
  }

  public function remitter()
  {
    if ($this->from_user_id == auth()->id()) {
      return $this->receiver;
    } 

    return $this->sender;
  }

  public function remitterName()
  {
    return $this->remitter()->hide_name == 'yes' 
      ? $this->remitter()->username 
      : $this->remitter()->name;
  }

  public function totalMsg()
  {
    return $this->where('from_user_id', $this->remitter()->id)
      ->where('to_user_id', auth()->id())
      ->where('status','new')
      ->count();
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'from_user_id')->first();
  }

  public static function markSeen()
  {
    $this->timestamps = false;
    $this->status = 'readed';
    $this->save();
  }

  public function media() 
  {
		return $this->hasMany(MediaMessages::class)->where('status', 'active')->orderBy('id','asc');
	}

  public function scopeGetMessageChat($query, $id, $skip = null)
  {
    $fields = 'id,avatar,name,username';

    $query->where('to_user_id', auth()->id())
      ->where('from_user_id', $id)
      ->whereMode('active')
      ->orWhere( 'from_user_id', auth()->id() )
      ->where('to_user_id', $id)
      ->whereMode('active');

      $query->when($skip, fn ($q) => 
  			$q->skip($skip)
  		);

      $query = $query->take(10)
      ->orderBy('messages.id', 'DESC')
      ->with(['sender:'.$fields, 'receiver:'.$fields, 'media'])
      ->get();

      return $query;
  }
}
