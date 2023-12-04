<?php

namespace App\Http\Controllers\Traits;

use DB;
use App\Helper;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Notifications;
use App\Models\Comments;
use App\Models\Like;
use App\Models\Media;
use App\Models\MediaMessages;
use App\Models\Updates;
use App\Models\Reports;
use App\Models\VerificationRequests;
use App\Models\PaymentGateways;
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\Products;
use App\Models\Bookmarks;
use App\Models\LoginSessions;
use App\Models\UserDevices;
use App\Models\Stories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


trait UserDelete {

	// START
	public function deleteUser($id)
	{
		$user     = User::findOrFail($id);
		$settings = AdminSettings::first();

		// Comments Delete
		$comments = Comments::where('user_id', $id)->get();

		// Delete Likes Comments
		foreach ($comments as $key) {
			$key->likes()->delete();
		}

		if (isset($comments)) {
			foreach ($comments as $comment){
				$comment->delete();
			}
		}

		// Delete Replies
		$user->replies()->delete();

		// Conversations Delete
		$conversations = Conversations::where('user_1',  $id)
				->orWhere('user_2', $id)
				->get();

		if (isset($conversations)) {
			foreach ($conversations as $conversation){
				$conversation->delete();
			}
		}

		// Likes
		$likes = Like::where('user_id', $id)->get();

		if (isset($likes)) {
			foreach ($likes as $like) {
				$like->delete();
			}
		}

		// Bookmarks
		$bookmarks = Bookmarks::where('user_id', $id)->get();

		if (isset($bookmarks)) {
			foreach ($bookmarks as $bookmark) {
				$bookmark->delete();
			}
		}

		// Messages Delete
		$path = config('path.messages');

		$messages = Messages::where('from_user_id', $id)
				->orWhere('to_user_id', $id)
				->get();

		if (isset($messages)) {
			foreach ($messages as $message) {

				$files = MediaMessages::whereMessagesId($message->id)->get();

				foreach ($files as $media) {

	        $messageWithSameFile = MediaMessages::whereFile($media->file)
	        ->where('id', '<>', $media->id)
	        ->count();

	        if ($messageWithSameFile == 0) {
	          Storage::delete($path.$media->file);
	          Storage::delete($path.$media->video_poster);
	        }
	        $media->delete();
	      }
				$message->delete();
			}
		}

		// Delete Notification
		$notifications = Notifications::where('author', $id)
				->orWhere('destination', $id)
					->get();

		if (isset($notifications)) {
			foreach ($notifications as $notification) {
				$notification->delete();
			}
		}

		// Reports
		$reports = Reports::where('user_id', $id)
				->orWhere('type', 'user')
				->where('report_id', $id)
					->get();

		if (isset($reports)) {
			foreach ($reports as $report) {
				$report->delete();
			}
		}

		// Subscriptions User
		$subscriptions = Subscriptions::whereUserId($id)->get();
		$payment       = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->first();


		if (isset($subscriptions)) {

			foreach ($subscriptions as $subscription) {
				 if ($subscription->stripe_id == '') {
				 		$subscription->delete();
				 } else {
					 try {
						 $stripe  = new \Stripe\StripeClient($payment->key_secret);
						 $stripe->subscriptions->cancel($subscription->stripe_id, []);
					 } catch (\Exception $e) {
					 }

					 if ($subscription->stripe_id != '') {
						 DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();
						 $subscription->delete();
					 }
				 }
			}
		} // Isset Stripe

		// Subscriptions Creator
		$subscriptionsCreator = Subscriptions::whereStripePrice($user->plan)->get();
		if (isset($subscriptionsCreator)) {
			foreach ($subscriptionsCreator as $subscription) {
				 if ($subscription->stripe_id != '') {
					 try {
						 $stripe  = new \Stripe\StripeClient($payment->key_secret);
						 $stripe->subscriptions->cancel($subscription->stripe_id, []);
					 } catch (\Exception $e) {
					 }

					 DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();
				 }
				 $subscription->delete();
			}
		}

		//<<<--  Delete All Products -->>>
		$items = Products::where('user_id', $user->id)->get();
		$pathShop = config('path.shop');

		foreach ($items as $item) {
			// Delete Notifications
	    Notifications::whereType(15)->whereTarget($item->id)->delete();

	    // Delete Preview
	    foreach ($item->previews as $previews) {
	      Storage::delete($pathShop.$previews->name);
	    }

	    // Delete file
	    Storage::delete($pathShop.$item->file);

	    // Delete purchases
	    $item->purchases()->delete();

	    // Delete item
	    $item->delete();
		}

		// Delete All Updates (Posts)
		$this->deleteUserUpdates($id);

		//<<<-- Delete Avatar -->>>/
		if ($user->avatar != $settings->avatar) {
			Storage::delete(config('path.avatar').$user->avatar);
		}

		//<<<-- Delete Cover -->>>/
		if ($user->cover != '') {
			Storage::delete(config('path.cover').$user->cover);
		}

		// Delete withdrawals
	  $withdrawals = $user->withdrawals()->whereStatus('pending')->get();

      if ($withdrawals) {
        foreach ($withdrawals as $withdrawal) {
          $withdrawal->delete();
        }
      }

	  // Delete Login Session
	  LoginSessions::whereUserId($user->id)->delete();

	  // User Devices
	  $oneSignalDevices = $user->oneSignalDevices()->get();

      if ($oneSignalDevices) {
        foreach ($oneSignalDevices as $oneSignalDevice) {
          $oneSignalDevice->delete();
        }
      }

	  // Stories Delete
	  $stories = Stories::whereUserId($id)->get();
	  $pathStories = config('path.stories');

		if (isset($stories)) {
			foreach ($stories as $story) {
				foreach ($story->media()->get() as $media) {
					$media->views()->delete();
					Storage::delete($pathStories.$media->name);
				}
				
				$story->delete();
			}
		}

		// User Delete
		$user->delete();

	}//<--- END METHOD

	protected function deleteUserUpdates($idUser)
	{
		$path      = config('path.images');
    	$pathVideo = config('path.videos');
    	$pathMusic = config('path.music');
		$pathFiles = config('path.files');

		// Delete Updates
		$updates = Updates::where('user_id', $idUser)->get();

		if (isset($updates)) {
			foreach($updates as $update) {

				$files = Media::whereUpdatesId($update->id)->get();

				foreach ($files as $media) {

					if ($media->image) {
		        Storage::delete($path.$media->image);
		        $media->delete();
		      }

		      if ($media->video) {
		        Storage::delete($pathVideo.$media->video);
		        Storage::delete($pathVideo.$media->video_poster);
		        $media->delete();
		      }

		      if ($media->music) {
		        Storage::delete($pathMusic.$media->music);
		        $media->delete();
		      }

		      if ($media->file) {
		        Storage::delete($pathFiles.$media->file);
		        $media->delete();
		      }

		      if ($media->video_embed) {
		        $media->delete();
		      }
				}

				$update->delete();

			}
		}
	}// End Method

}// End Class
