<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Customer;
use Dashboard\models\customer\Operator;

class Phones extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function operator(){
        return $this->belongsTo(Operator::class);
    }
}
