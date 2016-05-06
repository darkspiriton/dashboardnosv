<?php

namespace Dashboard\Models\Shipment;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table='shipments_status';

    public function shipments (){
        return $this->hasMany(Shipment::class);
    }
}
