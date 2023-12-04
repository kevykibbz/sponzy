<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\PushNotificationTrait;

class Notifications extends Model
{
	use PushNotificationTrait;

	protected $guarded = [];
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public static function send($userDestination, $userAuthor, $type, $target)
	{
		$settings = AdminSettings::select('push_notification_status')->first();
		$user   = User::find($userDestination);
		$author = User::find($userAuthor);
		$getPushNotificationDevices = $user->oneSignalDevices->pluck('player_id')->all();

		if ($type == 5 && $user->notify_new_tip == 'no' || $type == 6 && $user->notify_new_ppv == 'no') {
			return false;
		}

		self::create([
			'destination' => $userDestination,
			'author' => $userAuthor,
			'type' => $type,
			'target' => $target
		]);

		// Send push notification
		if ($settings->push_notification_status && $getPushNotificationDevices) {
			$authorName = $author->hide_name == 'yes' ? $author->username : $author->name;
			$post       = Updates::find($target);
			$postUrl    = $post ? url($post->user()->username . '/' . 'post', $post->id) : null;

			app()->setLocale($user->language);

			switch ($type) {
				case 1:
					$msg             = $authorName . ' ' . __('users.has_subscribed');
					$linkDestination = url('notifications');
					break;
				case 2:
					$msg             = $authorName . ' ' . __('users.like_you');
					$linkDestination = $postUrl;
					break;
				case 3:
					$msg             = $authorName . ' ' . __('users.comment_you');
					$linkDestination = $postUrl;
					break;
				case 4:
					$msg             = $authorName . ' ' . __('general.like_your_comment');
					$linkDestination = $postUrl;
					break;

				case 5:
					$msg             = $authorName . ' ' . __('general.has_sent_you_tip');
					$linkDestination = url('my/payments/received');
					break;

				case 6:
					$msg         	 = $authorName . ' ' . __('general.has_bought_your_message');
					$linkDestination = url('messages', $user->id);
					break;

				case 7:
					$msg         	 = $authorName . ' ' . __('general.has_bought_your_content');
					$linkDestination = $postUrl;
					break;

				case 8:
					$msg          	 = __('general.has_approved_your_post');
					$linkDestination = $postUrl;
					break;

				case 9:
					$msg          	 = __('general.video_processed_successfully_post');
					$linkDestination = $postUrl;
					break;

				case 10:
					$msg             = __('general.video_processed_successfully_message');
					$linkDestination = url('notifications');
					break;

				case 11:
					$msg             = __('general.referrals_made_transaction');
					$linkDestination = url('my/referrals');
					break;

				case 12:
					$msg         	 = __('general.payment_received_subscription_renewal');
					$linkDestination = url('my/payments/received');
					break;

				case 13:
					$msg          	 = $authorName . ' ' . __('general.has_changed_subscription_paid');
					$linkDestination = url($author->username);
					break;

				case 14:
					$msg             = $authorName . ' ' . __('general.is_streaming_live');
					$linkDestination = url('live', $author->username);
					break;

				case 15:
					$msg         	 = $authorName . ' ' . __('general.has_bought_your_item');
					$linkDestination = url('my/sales');
					break;

				case 16:
					$msg             = $authorName . ' ' . __('general.has_mentioned_you_post');
					$linkDestination = $postUrl;
					break;

				case 17:
					$msg             = __('general.story_successfully_posted');
					$linkDestination = url('/');
					break;

				case 18:
					$msg             = __('general.body_account_verification_approved');
					$linkDestination = url('/');
					break;

				case 19:
					$msg             = __('general.body_account_verification_reject');
					$linkDestination = url('/');
					break;

				case 20:
					$msg             = __('general.error_video_encoding_post');
					$linkDestination = url('my/posts');
					break;

				case 21:
					$msg             = __('general.error_video_encoding_message');
					$linkDestination = url('messages');
					break;
				case 22:
					$msg             = __('general.error_video_encoding_story');
					$linkDestination = url('my/stories');
					break;

				case 23:
					$msg             = $authorName . ' ' . __('general.has_sent_private_live_stream_request');
					$linkDestination = url('my/live/private/requests');
					break;

				case 24:
					$msg             = __('general.video_processed_successfully_welcome_message');
					$linkDestination = url('settings/conversations');
					break;

				case 25:
					$msg             = __('general.error_video_encoding_welcome_msg');
					$linkDestination = url('settings/conversations');
					break;
			}

			try {
				// Send push notification
				PushNotificationTrait::sendPushNotification($msg, $linkDestination, $getPushNotificationDevices);
			} catch (\Exception $e) {
				\Log::info('Push Notification Error - ' . $e->getMessage());
			}
		}
	}
}
