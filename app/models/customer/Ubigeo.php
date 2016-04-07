<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Address;

class Ubigeo extends Model
{
    public function Adressess(){
        return $this->hasMany(Address::class);
    }
}
