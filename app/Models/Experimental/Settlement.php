<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = "settlements";

    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
}
