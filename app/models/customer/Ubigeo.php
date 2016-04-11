<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $table = 'ubigeos';

    public $timestamps = false;

    public function Adressess(){
        return $this->hasMany(Address::class);
    }
}