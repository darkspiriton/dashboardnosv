<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Dashboard\Models\Kardex\AttributesKardex;
use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class GroupAttribute extends Model
{
    protected $table = 'groups_attributes';
    public function kardexs(){
        return $this->hasMany(Kardex::class);
    }

    public function attributes_kardex(){
        return $this->hasMany(AttributesKardex::class)->select(array("id","group_attribute_id","attribute_id"));
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
