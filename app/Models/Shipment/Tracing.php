<?php

namespace Dashboard\Models\Shipment;

use Dashboard\Dashboard\Models\Shipment\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Tracing extends Model
{
    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
