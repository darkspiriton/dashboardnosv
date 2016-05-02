<?php

namespace Dashboard\Listeners;

use Dashboard\Dashboard\Models\Kardex\AttributesKardex;
use Dashboard\Events\KardexWasCreateProduct;
use Dashboard\Models\Kardex\GroupAttribute;
use Dashboard\Models\Kardex\Kardex;

class KardexCreateStock
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
     * @param  SomeEvent  $event
     * @return void
     */
    public function handle(KardexWasCreateProduct $event)
    {
//        $data=$event->getData();
//        $id=$data['id'];
//        $groups = json_decode($data['groups'], true);
//        foreach($groups as $group){
//            $this->addKardex($id, $group['quantity'] , $group['attributes']);
//        }

        $data=$event->getData();
        $id=$data['id'];
//      $groups = json_decode($data['groups'], true);
        foreach($data['groups'] as $group){
            $this->addKardex($id, $group['quantity'] , $group['attributes']);
        }
    }

    private function addKardex($id,$cant,$attributes){
        $group= new GroupAttribute();
        $group->product_id=$id;
        $group->save();

        foreach($attributes as $attribute ){
            $k_attr = new AttributesKardex();
            $k_attr->attribute_id = $attribute['val_id'];
            $group->attributes()->save($k_attr);
        }

        for ($i=0;$i<$cant;$i++){
            $kard = new Kardex();
            $kard->product_id=$id;
            $kard->stock=true;
            $group->kardexs()->save($kard);
        }
    }
    
}
