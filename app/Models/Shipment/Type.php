<?php

namespace Dashboard\Models\Shipment;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table='types_shipments';

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }
}
