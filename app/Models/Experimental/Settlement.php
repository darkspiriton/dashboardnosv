<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table="settlements";
    
    protected function product(){
        return $this->hasOne(Product::class);
    }
    
}
