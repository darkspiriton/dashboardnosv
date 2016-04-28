<?php

namespace Dashboard\Models\Shipment;

use Dashboard\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{


    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function routes(){
        return $this->hasMany(Route::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
