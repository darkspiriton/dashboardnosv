<?php

namespace Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{

    protected  $table='kardexs';

    public function movements(){
        return $this->hasMany(Movements::class);
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class);
    }
}

