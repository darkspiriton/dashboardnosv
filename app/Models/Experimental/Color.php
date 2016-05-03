<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table="colors";
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
