<?php

namespace Dashboard\Events;

use Dashboard\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationPusher extends Event
{
    private $data;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title, $body, $event, $type_id)
    {
        $this->data["title"]    = $title;
        $this->data["body"]     = $body;
        $this->data["event"]    = $event;
        $this->data["type_id"]  = $type_id;
        $this->data["status"]   = 0;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function getData(){
        return $this->data;
    }
}
