<?php

namespace Dashboard\Models\Customer;

use Dashboard\Models\Interest\Interest;
use Dashboard\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Dashboard\User;

class Customer extends Model
{
    protected $table = 'customers';

    protected $hidden = [
        'created_at','updated_at'
    ];

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

    public function interests(){
        return $this->hasMany(Interest::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    
}
