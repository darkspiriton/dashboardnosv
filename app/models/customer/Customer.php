<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function phones(){
        return $this->hasMany(Phone::class);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function socials(){
        return $this->hasMany(Social::class);
    }
}
