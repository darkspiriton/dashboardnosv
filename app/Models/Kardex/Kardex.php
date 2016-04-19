<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Dashboard\Models\Kardex\Attribute;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    
    public function movements(){
        return $this->hasMany(Movements::class);
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class);
    }
}

