<?php

namespace Dashboard\Dashboard\Models\Shipment;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{

    protected $table='routes_sheets';

    public function shipments(){
        return $this->belongsToMany(Shipment::class);
    }
}
