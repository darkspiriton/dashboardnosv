<?php

namespace Dashboard\Listeners;

use Dashboard\Events\NotificationPusherWasCheck;
use Dashboard\Models\Notification\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Vinkla\Pusher\Facades\Pusher;

class NotificationPusherWasCheckListener
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
     * @param  NotificationPusherWasCheck  $event
     * @return void
     */
    public function handle(NotificationPusherWasCheck $event)
    {
        $data = $event->getData();

        if ($data instanceof Notification){
            Pusher::trigger('notification','update', [$data->toArray()]);
        } else if($data instanceof Collection){
            Pusher::trigger('notification','update', $data->toArray());
        }
    }
}
