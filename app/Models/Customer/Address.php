<?php

namespace Dashboard\Models\Customer;

use Dashboard\Models\Shipment\Shipment;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function ubigeo(){
        return $this->belongsTo(Ubigeo::class);
    }

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }
}
