<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Customer;
use Dashboard\models\customer\Ubigeo;

class Address extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function ubigeo(){
        return $this->belongsTo(Ubigeo::class);
    }
}
