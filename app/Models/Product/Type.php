<?php

namespace Dashboard\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table='types_products';

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
