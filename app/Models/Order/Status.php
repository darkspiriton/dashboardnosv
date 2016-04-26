<?php

namespace Dashboard\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
