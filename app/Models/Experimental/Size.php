<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table="sizes";

    protected $hidden = ['created_at','updated_at'];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
