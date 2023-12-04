<?php

namespace App\Providers;

use App\Providers\SubscriptionDisabledEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscriptionDisabledListener
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
     * @param  \App\Providers\SubscriptionDisabledEvent  $event
     * @return void
     */
    public function handle(SubscriptionDisabledEvent $event)
    {
        //
    }
}
