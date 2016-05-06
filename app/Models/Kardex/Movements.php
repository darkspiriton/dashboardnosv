<?php

namespace Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Movements extends Model
{

    protected $table='movements';

    public function kardex(){
        return $this->belongsTo(Kardex::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

}
