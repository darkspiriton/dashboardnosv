<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $hidden = [
        'created_at','updated_at'
    ];

    protected $table = 'kardexs';

    public function movements(){
        return $this->hasMany(Movements::class);
    }

    public function group_kardex(){
        return $this->belongsTo(GroupAttribute::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}

