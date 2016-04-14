<?php

namespace Dashboard;

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
        'password',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
