<?php

namespace App\Providers;

use App\Events\NewPostEvent;
use App\Listeners\NewPostListener;
use App\Events\MassMessagesEvent;
use App\Listeners\MassMessagesListener;
use App\Events\SubscriptionDisabledEvent;
use App\Listeners\SubscriptionDisabledListener;
use App\Events\LiveBroadcasting;
use App\Listeners\LiveBroadcastingListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewPostEvent::class => [
            NewPostListener::class,
        ],
        MassMessagesEvent::class => [
            MassMessagesListener::class,
        ],
        SubscriptionDisabledEvent::class => [
          SubscriptionDisabledListener::class,
        ],
        LiveBroadcasting::class => [
          LiveBroadcastingListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
