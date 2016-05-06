<?php

namespace Dashboard\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table='status_order';    
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
