<?php

namespace Dashboard\Models\Interest;

use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table = 'interest_details';

    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function interest(){
        return $this->belongsTo(Interest::class);
    }
}
