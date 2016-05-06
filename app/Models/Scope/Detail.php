<?php

namespace Dashboard\Models\Scope;

use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected  $table='scope_details';
    public $timestamps = false;

    public function scope(){
        return $this->belongsTo(Scope::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
