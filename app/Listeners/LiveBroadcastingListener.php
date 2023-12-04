<?php

namespace App\Listeners;

use App\Events\LiveBroadcasting;
use App\Models\Notifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LiveBroadcastingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LiveBroadcasting  $event
     * @return void
     */
    public function handle(LiveBroadcasting $event)
    {
        $user   = $event->user;
        $liveId = $event->liveId;

        // Get Subscriptions Active
        $subscriptionsActive = $user->mySubscriptions()
            ->where('stripe_id', '=', '')
              ->where('ends_at', '>=', now())
              ->where('cancelled', '=', 'no')
            ->orWhere('stripe_status', 'active')
                ->where('stripe_id', '<>', '')
                  ->whereIn('stripe_price', $user->plans()->pluck('name'))
              ->orWhere('stripe_id', '=', '')
                ->where('free', '=', 'yes')
                ->whereIn('stripe_price', $user->plans()->pluck('name'))
                    ->chunk(500, function ($subscriptions) use ($user, $liveId) {
                      foreach ($subscriptions as $subscription) {
                        if ($subscription->user()->notify_live_streaming == 'yes') {
                          // Notify to subscriber - Destination, Author, Type, Target
                          Notifications::send($subscription->user_id, $user->id, 14, $liveId);
                        }
                      }
                    });
    }
}
