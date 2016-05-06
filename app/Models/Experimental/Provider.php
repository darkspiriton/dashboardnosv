<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = "providers";

    protected $hidden = ['created_at','updated_at'];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
