<?php

namespace Dashboard\Models\Shipment;

use Dashboard\Models\Customer\Address;
use Dashboard\Models\Kardex\Movements;
use Dashboard\Models\Order\Order;
use Dashboard\Models\Sale\Sale;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table='shipments';

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

//    public function routes(){
//        return $this->hasMany(Route::class);
//    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function tracings(){
        return $this->hasMany(Tracing::class);
    }

    public function movements(){
        return $this->hasMany(Movements::class);
    }
}