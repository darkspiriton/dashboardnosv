<?php

namespace Dashboard\Models\Sale;

use Dashboard\Dashboard\Models\Shipment\Shipment;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    
    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

    public function commissions(){
        return $this->hasMany(Commission::class);
    }

}
