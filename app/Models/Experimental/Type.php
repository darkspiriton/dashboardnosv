<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table="types";

    protected $hidden = ['created_at','updated_at'];
    
    public function products(){
        return $this->belongsToMany(Product::class,"types_auxproducts","product_id");
    }
}
