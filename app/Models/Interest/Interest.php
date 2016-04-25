<?php

namespace Dashboard\Models\Interest;

use Dashboard\Models\Customer\Channel;
use Dashboard\Models\Customer\Customer;
use Dashboard\User;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table = 'interests';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function channel(){
        return $this->belongsTo(Channel::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    
    public function details(){
        return $this->hasMany(Interest::class);
    }
}
