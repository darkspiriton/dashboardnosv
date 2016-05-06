<?php

namespace Dashboard\Listeners;

use Dashboard\Events\ProductWasCreated;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\Alarm;

class ProductCreateStock
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
     * @param  ProductWasCreated  $event
     * @return void
     */
    public function handle(ProductWasCreated $event)
    {
        $data=$event->getData();

        // Si el validador pasa, almacenamos la alarma
        $alarm = new Alarm();
        $alarm->day = $data['day'];
        $alarm->count = $data['count'];
        $alarm->save();

        $cant=$data['cant'];
        $cod= $data['cod'];
        for($i=0;$i<$cant;$i++){
            $product = new Product();
            $product->cod= $cod+$i;
            $product->provider_id= $data['provider_id'];
            $product->color_id= $data['color_id'];
            $product->size_id= $data['size_id'];
            $product->alarm_id= $alarm->id;
            $product->name= $data['name'];
            $product->status= 1;
            $product->save();
        }
    }
}
