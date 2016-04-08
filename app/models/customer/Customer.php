<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\User;

class Customer extends Model
{
    protected $table = 'customers';

    public function phones(){
        return $this->hasMany(Phone::class);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function socials(){
        return $this->hasMany(Social::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
