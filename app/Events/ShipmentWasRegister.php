<?php

namespace Dashboard\Events;

use Illuminate\Queue\SerializesModels;

class ShipmentWasRegistered extends Event
{
    use SerializesModels;
    protected $data;

    /**
     * UserWasRegistered constructor.
     * @param array $data
     * @param $verification_code
     */
    public function __construct(array $data)
    {
        $this->data=$data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}
