<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;


class Ubigeo extends Model
{
    public function Adressess(){
        return $this->hasMany(Address::class);
    }
}
