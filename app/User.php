<?php

namespace Dashboard;

use Dashboard\Models\Order\Order;
use Dashboard\Models\Scope\Scope;
use Dashboard\Models\Request\Product;
use Dashboard\Models\Sale\Commission;
use Dashboard\Models\Shipment\Tracing;
use Dashboard\Models\Customer\Customer;
use Dashboard\Models\Interest\Interest;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Request\Application;

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

    public function setTokenAttribute($value){
        return $this->attributes['token']=$value;
    }

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

    public function getFullNameAttribute(){
        return $this->last_name.','.' '.$this->first_name;
    }

    public function requestAplications(){
        $this->hasMany(Application::class);
    }

    public function requestProducts(){
        $this->hasMany(Product::class);
    }

}
