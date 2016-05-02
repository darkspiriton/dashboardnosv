<?php

namespace Dashboard\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $table='calls';
    public $timestamps = false;

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function status(){
        return $this->belongsTo(StatusCall::class);
    }
    
}
