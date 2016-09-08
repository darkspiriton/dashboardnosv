<?php

namespace Dashboard\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Dashboard\Events\PrestaShopWasCreated' => [
            'Dashboard\Listeners\SendSmsListener',
        ],
        'Dashboard\Events\OrderPrestashopWasCreated' => [
            'Dashboard\Listeners\OrderPrestashopMailCreate',
        ],
        'Dashboard\Events\NotificationPusher' => [
            'Dashboard\Listeners\NotificationPusherListener',
        ],
        'Dashboard\Events\NotificationPusherWasCheck' => [
            'Dashboard\Listeners\NotificationPusherWasCheckListener',
        ],
        'Dashboard\Events\ProductStatusWasChanged' => [
            'Dashboard\Listeners\ProdcutStatusWasChangedListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
