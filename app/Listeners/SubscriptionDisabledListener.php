<?php

namespace App\Listeners;

use App\Events\SubscriptionDisabledEvent;
use App\Notifications\SubscriptionDisabled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notifications;

class SubscriptionDisabledListener implements ShouldQueue
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
     * @param  \App\Events\SubscriptionDisabledEvent  $event
     * @return void
     */
    public function handle(SubscriptionDisabledEvent $event)
    {
        $user = $event->user;
        $freeSubscription = $event->freeSubscription;

        if ($freeSubscription == 'yes') {
          // Get Subscriptions Active Paid
          $subscriptionsActive = $user->mySubscriptions()
                ->where('stripe_id', '=', '')
                  ->where('ends_at', '>=', now())
                  ->where('cancelled', '=', 'no')
                  ->orWhere('stripe_status', 'active')
                    ->where('stripe_id', '<>', '')
                      ->whereIn('stripe_price', $user->plans()->pluck('name'))
                      ->chunk(500, function ($subscriptions) use ($user) {
                        foreach ($subscriptions as $subscription) {

                          try {
                            $subscription->user()->notify(new SubscriptionDisabled($user));
                          } catch (\Exception $e) {
                            \Log::info($e->getMessage());
                          }
                        }
                      });
        } else {
          // Get Subscriptions Active Free
          $subscriptionsActive = $user->mySubscriptions()
                ->where('stripe_id', '=', '')
                  ->where('free', '=', 'yes')
                      ->chunk(500, function ($subscriptions) use ($user) {
                        foreach ($subscriptions as $subscription) {
                          // Notify to subscriber - Destination, Author, Type, Target
                          Notifications::send($subscription->user_id, $user->id, 13, $user->id);

                          // Delete Subscription
                          $subscription->delete();
                        }
                      });
        }
    }
}
