<?php

namespace Dashboard\Listeners;

use Dashboard\Events\PrestaShopWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio;

class SendSmsListener
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
     * @param  PrestaShopWasCreated  $event
     * @return void
     */
    public function handle(PrestaShopWasCreated $event)
    {
        $data=$event->getData();
        try{
            Twilio::message($data->user->phone,"Mensaje de Prueba");
        }catch(\Services_Twilio_RestException $e){
            $e->getMessage();
        }        

    }
}
