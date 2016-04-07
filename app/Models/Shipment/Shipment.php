<?php

namespace Dashboard\Dashboard\Models\Shipment;

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
}
