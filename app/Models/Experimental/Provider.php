<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = "providers";
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
