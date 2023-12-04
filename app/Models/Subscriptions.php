<?php

namespace App\Models;

use Mail;
use App\Models\User;
use App\Models\Notifications;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
	protected $guarded = [];

	public function subscriber()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function creator()
	{
		return $this->belongsTo(User::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function subscribed()
	{
		return $this->belongsToMany(
			User::class,
			Plans::class,
			'name',
			'user_id',
			'stripe_price',
			'id'
		)->first();
	}

	public static function sendEmailAndNotify($subscriber, $user)
	{
		$user = User::select([
			'id',
			'language',
			'email',
			'name',
			'email_new_subscriber',
			'notify_new_subscriber'
		])->whereId($user)->first();

		// Set Lang user
		app()->setLocale($user->language);

		$titleSite    = config('settings.title');
		$sender       = config('settings.email_no_reply');
		$emailUser    = $user->email;
		$fullNameUser = $user->name;
		$subject      = $subscriber . ' ' . __('users.has_subscribed');

		try {
			if ($user->email_new_subscriber == 'yes') {
				Mail::send(
					'emails.new_subscriber',
					[
						'body' => $subject,
						'title_site' => $titleSite,
						'fullname' => $fullNameUser
					],
					function ($message) use ($sender, $subject, $fullNameUser, $titleSite, $emailUser) {
						$message->from($sender, $titleSite)
							->to($emailUser, $fullNameUser)
							->subject($subject . ' - ' . $titleSite);
					}
				);
			}
		} catch (\Exception $e) {
			\Log::info('Error send email new Subscriber ---' . $e->getMessage());
		}

		if ($user->notify_new_subscriber == 'yes') {
			// send(destination, author, type, target)
			Notifications::send($user->id, auth()->id(), '1', $user->id);
		}
	}
}
