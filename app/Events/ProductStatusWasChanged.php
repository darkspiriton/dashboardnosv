<?php

namespace Dashboard\Events;

use Dashboard\Events\Event;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\ProductStatusDetail;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ProductStatusWasChanged extends Event
{
    private $product;
    private $status_detail;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Product $product, ProductStatusDetail $status_detail = null)
    {
        $this->product = $product;
        $this->status_detail = $status_detail;
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

    public function getProduct()
    {
        return $this->product;
    }

    public function getStatusDetail()
    {
        return $this->status_detail;
    }
}
