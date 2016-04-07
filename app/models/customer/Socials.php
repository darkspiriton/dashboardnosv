<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Channel;
use Dashboard\models\customer\Customer;

class Socials extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function channel(){
        return $this->belongsTo(Channel::class);
    }
}
