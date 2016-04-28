<?php

namespace Dashboard\Models\Order;

use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $timestamps = false;

    protected $table = 'order_details';

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
}
