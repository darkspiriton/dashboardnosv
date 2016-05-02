<?php

namespace Dashboard\Models\Order;


use Dashboard\Models\Shipment\Shipment;
use Dashboard\Models\Customer\Customer;
use Dashboard\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function customer(){
        return $this->belongsTo(Customer::class)->select('name');
    }

    public function calls(){
        return $this->hasMany(Call::class);
    }

    public function details(){
        return $this->hasMany(Detail::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->select(array('last_name', 'first_name'));
    }

    public function shipment(){
        return $this->hasMany(Shipment::class);
    }

}
