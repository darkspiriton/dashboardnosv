<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function movements(){
        return $this->hasMany(Movements::class);
    }
}
