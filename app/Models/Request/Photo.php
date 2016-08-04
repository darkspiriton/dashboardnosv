<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\Product;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos_products';
    protected $id = 'id';

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
