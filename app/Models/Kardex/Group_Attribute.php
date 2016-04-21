<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Dashboard\Models\Kardex\Attribute_Kardex;
use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Group_Attribute extends Model
{
    protected $table='groups_attributes';
    public function kardexs(){
        return $this->hasMany(Kardex::class);
    }

    public function attributes(){
        return $this->hasMany(Attribute_Kardex::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
