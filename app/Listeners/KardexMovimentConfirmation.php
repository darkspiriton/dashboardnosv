<?php

namespace Dashboard\Listeners;

use Dashboard\Events\ShipmentWasRegister;

class KardexMovimentConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ShipmentWasRegister  $event
     * @return void
     */
    public function handle(ShipmentWasRegister $event)
    {
        //Falta implementar logica para separar los productos del kardex cambiarles de estados
        
        $data=$event->getData();
    }
}
