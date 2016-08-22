<?php

namespace Dashboard\Listeners;

use Dashboard\Events\NotificationPusher;
use Dashboard\Models\notification\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Vinkla\Pusher\Facades\Pusher;
use Log;

class NotificationPusherListener
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
     * @param  NotificationPusher  $event
     * @return void
     */
    public function handle(NotificationPusher $event)
    {
        $notification = Notification::create($event->getData());
        Pusher::trigger('notification','create', $notification->toArray());
    }
}
