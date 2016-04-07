<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    public function movements(){
        return $this->hasMany(Movements::class);
    }
}

