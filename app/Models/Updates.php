<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper;

class Updates extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function creator()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function media()
	{
		return $this->hasMany(Media::class)->where('status', 'active')->orderBy('id','asc');
	}

	public function likes()
	{
		return $this->hasMany(Like::class)->where('status', '1');
	}

	public function comments()
	{
		return $this->hasMany(Comments::class);
	}

	public function replies()
	{
		return $this->hasMany(Replies::class);
	}

	public function bookmarks()
	{
		return $this->belongsToMany(User::class, 'bookmarks','updates_id','user_id');
	}

	public function totalComments()
	{
		$post = $this->withCount(['comments', 'replies'])->whereId($this->id)->get();

		return number_format($post[0]->comments_count + $post[0]->replies_count);
	}

	public function videoViews()
	{
		return $this->hasMany(VideoViews::class);
	}

	public function scopeGetSelectRelations($query)
	{
		return $query->select('updates.id',
                'updates.title',
                'updates.description',
                'updates.user_id',
                'updates.date',
                'updates.locked',
                'updates.fixed_post',
                'updates.price',
                'updates.status',
                'updates.video_views',
				'updates.scheduled_date'
                )
          ->with([
            'creator:id,name,username,avatar,hide_name,verified_id,plan,free_subscription,cover', 
            'creator.plans:user_id,name,status', 
            'media', 
            'comments:id,updates_id',
			'likes:id,updates_id,status'
		  ]);
	}

	
	public function scopeSelectPostsFields($query)
	{
	  return $query->select('updates.id',
		'updates.title',
		'updates.description',
		'updates.user_id',
		'updates.date',
		'updates.locked',
		'updates.fixed_post',
		'updates.price',
		'updates.status',
		'updates.video_views',
		'updates.scheduled_date'
	  );
	}

	public function scopeVerifyCountryBlocking($query)
	{
		$query->with(['creator' => fn ($sql) => 
			$sql->where('blocked_countries', 'NOT LIKE', '%'.Helper::userCountry().'%')
		]);
	}
}
