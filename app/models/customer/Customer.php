<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\Customer\Phones;
use Dashboard\models\customer\Address;
use Dashboard\models\customer\Socials;

class Customer extends Model
{
    public function phones(){
        return $this->hasMany(Phones::class);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function socials(){
        return $this->hasMany(Socials::class);
    }
}
