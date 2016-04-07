<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function ubigeo(){
        return $this->belongsTo(Ubigeo::class);
    }
}
