<?php

namespace Dashboard;

use Dashboard\Models\Interest\Interest;
use Dashboard\Models\Order\Order;
use Dashboard\Models\Sale\Commission;
use Dashboard\Models\Scope\Scope;
use Dashboard\Models\Shipment\Tracing;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Customer\Customer;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','token'
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }

    public function scopes(){
        return $this->hasMany(Scope::class);
    }

    public function interests(){
        return $this->hasMany(Interest::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

//    public function bonifications(){
//        return $this->hasMany(Bonification::class);
//    }

    public function tracings(){
        return $this->hasMany(Tracing::class);
    }

    public function commissions(){
        return $this->hasMany(Commission::class);
    }


}
