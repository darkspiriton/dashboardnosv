<?php

namespace Dashboard\Listeners;

use Dashboard\Events\OrderPrestashopWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \Mail;
use Log;

class OrderPrestashopMailCreate
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
     * @param  OrderPrestashopWasCreated  $event
     * @return void
     */
    public function handle(OrderPrestashopWasCreated $event)
    {
        $data       =   $event->getData();
        $person     =   $data["person"];
        $products   =   $data["products"];

        // dd($data);

        Mail::send('emails.order', $data, function ($message) use ($person) {

            $message->from("confirmacion@nosvenden.com", "www.nosvenden.com");
            $message->subject($person->name." - Confirmacion de recepción pedido.");
            $message->to("luizhito.lp.4ever@gmail.com", $person->name);
        
        });

        Log::info('test event success');
    }
}
